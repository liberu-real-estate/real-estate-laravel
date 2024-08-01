<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaseAgreementReady;
use Illuminate\Notifications\Notification as BaseNotification;
use Carbon\Carbon;

class NotificationService
{
    public function notifyLeaseAgreementReady(User $user, $leaseAgreementId)
    {
        Notification::send($user, new LeaseAgreementReady($leaseAgreementId));
    }

    public function scheduleNotification(User $user, BaseNotification $notification, Carbon $sendAt)
    {
        $user->notify((new $notification)->delay($sendAt));
    }

    public function sendSmsNotification(User $user, string $message)
    {
        // Implement SMS sending logic here
        // This could involve using a third-party SMS service
    }
}