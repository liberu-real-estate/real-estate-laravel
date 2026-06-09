<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_x_content_type_options_header_is_nosniff()
    {
        $response = $this->get('/');

        $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
    }

    public function test_x_frame_options_header_is_sameorigin()
    {
        $response = $this->get('/');

        $this->assertEquals('SAMEORIGIN', $response->headers->get('X-Frame-Options'));
    }

    public function test_x_xss_protection_header_is_set()
    {
        $response = $this->get('/');

        $this->assertEquals('1; mode=block', $response->headers->get('X-XSS-Protection'));
    }

    public function test_referrer_policy_header_is_set()
    {
        $response = $this->get('/');

        $this->assertEquals('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
    }

    public function test_security_headers_are_present_on_web_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertNotNull($response->headers->get('X-Content-Type-Options'));
        $this->assertNotNull($response->headers->get('X-Frame-Options'));
        $this->assertNotNull($response->headers->get('X-XSS-Protection'));
    }
}
