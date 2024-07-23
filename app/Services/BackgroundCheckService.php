<?php

namespace App\Services;

class BackgroundCheckService
{
    public function check($tenantId)
    {
        // Placeholder for actual background check API call
        $status = rand(0, 1) ? 'passed' : 'failed';
        return $status;
    }
}