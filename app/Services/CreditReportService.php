<?php

namespace App\Services;

class CreditReportService
{
    public function check($tenantId)
    {
        // Placeholder for actual credit report API call
        $status = rand(0, 1) ? 'good' : 'poor';
        return $status;
    }
}