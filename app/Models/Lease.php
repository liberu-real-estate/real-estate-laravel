<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\BlockchainService;

class Lease extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'tenant_id',
        'start_date',
        'end_date',
        'rent_amount',
        'status',
        'smart_contract_address',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function renew()
    {
        $this->end_date = $this->end_date->addYear();
        $this->status = 'active';
        $this->save();

        // Trigger notification
        $this->notifyRenewal();
    }

    public function terminate()
    {
        $this->status = 'terminated';
        $this->end_date = now();
        $this->save();

        // Trigger notification
        $this->notifyTermination();
    }

    protected function notifyRenewal()
    {
        $notificationService = app(LeaseNotificationService::class);
        $notificationService->sendRenewalNotification($this);
    }

    protected function notifyTermination()
    {
        $notificationService = app(LeaseNotificationService::class);
        $notificationService->sendTerminationNotification($this);
    }

    public function checkCompliance()
    {
        $complianceChecks = [
            $this->checkLeaseDuration(),
            $this->checkRentAmount(),
            $this->checkSecurityDeposit(),
        ];

        return !in_array(false, $complianceChecks);
    }

    protected function checkLeaseDuration()
    {
        $minDuration = config('leases.min_duration');
        $maxDuration = config('leases.max_duration');
        $duration = $this->end_date->diffInMonths($this->start_date);

        return $duration >= $minDuration && $duration <= $maxDuration;
    }

    protected function checkRentAmount()
    {
        $maxRentIncrease = config('leases.max_rent_increase');
        $previousLease = $this->property->leases()->where('end_date', '<', $this->start_date)->latest()->first();

        if (!$previousLease) {
            return true;
        }

        $rentIncrease = ($this->rent_amount - $previousLease->rent_amount) / $previousLease->rent_amount;
        return $rentIncrease <= $maxRentIncrease;
    }

    protected function checkSecurityDeposit()
    {
        $maxSecurityDeposit = config('leases.max_security_deposit') * $this->rent_amount;
        return $this->security_deposit <= $maxSecurityDeposit;
    }

    public function getSmartContractData()
    {
        $blockchainService = new BlockchainService();
        return $blockchainService->callContractMethod($this->smart_contract_address, 'getContractDetails', []);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
