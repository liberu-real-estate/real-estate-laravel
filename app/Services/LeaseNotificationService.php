<?php

namespace App\Services;

use App\Models\Lease;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaseRenewed;
use App\Notifications\LeaseTerminated;

class LeaseNotificationService
{
    public function sendRenewalNotification(Lease $lease)
    {
        Notification::send($lease->tenant, new LeaseRenewed($lease));
    }

    public function sendTerminationNotification(Lease $lease)
    {
        Notification::send($lease->tenant, new LeaseTerminated($lease));
    }
}