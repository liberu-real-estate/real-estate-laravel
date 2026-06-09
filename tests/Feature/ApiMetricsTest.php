<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiMetricsTest extends TestCase
{
    public function test_metrics_endpoint_returns_200()
    {
        $response = $this->getJson('/api/metrics');

        $response->assertStatus(200);
    }

    public function test_metrics_endpoint_returns_app_name()
    {
        $response = $this->getJson('/api/metrics');

        $response->assertJsonStructure(['app_name']);
    }

    public function test_metrics_endpoint_returns_environment()
    {
        $response = $this->getJson('/api/metrics');

        $response->assertJsonStructure(['environment']);
    }

    public function test_metrics_endpoint_returns_php_version()
    {
        $response = $this->getJson('/api/metrics');

        $response->assertJsonStructure(['php_version']);
    }

    public function test_metrics_endpoint_returns_cache_status()
    {
        $response = $this->getJson('/api/metrics');

        $response->assertJsonStructure(['cache_status']);
    }

    public function test_metrics_endpoint_returns_db_status()
    {
        $response = $this->getJson('/api/metrics');

        $response->assertJsonStructure(['db_status']);
    }

    public function test_metrics_endpoint_requires_no_authentication()
    {
        $response = $this->getJson('/api/metrics');

        $response->assertStatus(200);
    }

    public function test_metrics_endpoint_returns_all_required_keys()
    {
        $response = $this->getJson('/api/metrics');

        $response->assertJsonStructure([
            'app_name',
            'environment',
            'php_version',
            'cache_status',
            'db_status',
        ]);
    }
}
