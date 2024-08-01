<?php

namespace App\Services;

use App\Models\User;
use App\Models\Lease;
use App\Models\Appointment;
use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaseAgreementReady;
use App\Notifications\LeaseRenewalReminder;
use App\Notifications\AppointmentCreated;
use App\Notifications\AppointmentReminder;
use App\Notifications\MaintenanceRequestSubmitted;
use App\Notifications\MaintenanceRequestUpdated;
use App\Notifications\WorkOrderCreated;
use Illuminate\Notifications\Notification as BaseNotification;
use Carbon\Carbon;

use Illuminate\Support\Facades\Http;

use App\Models\Lead;
use App\Notifications\LeadFollowUp;
use App\Notifications\LeadReminder;

class NotificationService
{
    // ... (keep existing methods)

    public function notifyTenantRequestSubmitted(User $tenant, MaintenanceRequest $request)
    {
        Notification::send($tenant, new MaintenanceRequestSubmitted($request));
    }

    public function notifyTenantRequestUpdated(User $tenant, MaintenanceRequest $request)
    {
        Notification::send($tenant, new MaintenanceRequestUpdated($request));
    }

    public function notifyTenantWorkOrderCreated(User $tenant, WorkOrder $workOrder)
    {
        Notification::send($tenant, new WorkOrderCreated($workOrder));
    }

    // ... (keep existing methods)
}