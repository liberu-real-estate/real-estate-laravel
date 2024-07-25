<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaseAgreementReady;

class NotificationService
{
    public function notifyLeaseAgreementReady(User $user, $leaseAgreementId)
    {
        Notification::send($user, new LeaseAgreementReady($leaseAgreementId));
    }
}