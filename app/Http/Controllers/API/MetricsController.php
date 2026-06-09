<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{
    public function index(): JsonResponse
    {
        $metrics = [
            'app_name' => config('app.name'),
            'app_version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'php_version' => PHP_VERSION,
            'uptime' => $this->getUptime(),
            'cache_status' => $this->getCacheStatus(),
            'db_status' => $this->getDatabaseStatus(),
        ];

        return response()->json($metrics);
    }

    private function getUptime(): string
    {
        $start = Cache::remember('app_start_time', 86400, fn() => now()->toIso8601String());
        return $start;
    }

    private function getCacheStatus(): string
    {
        try {
            Cache::put('_health_check', true, 10);
            return Cache::get('_health_check') ? 'healthy' : 'degraded';
        } catch (\Exception) {
            return 'unhealthy';
        }
    }

    private function getDatabaseStatus(): string
    {
        try {
            DB::connection()->getPdo();
            return 'healthy';
        } catch (\Exception) {
            return 'unhealthy';
        }
    }
}
