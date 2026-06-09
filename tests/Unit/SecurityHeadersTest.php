<?php

namespace Tests\Unit;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_x_content_type_options_header_is_set()
    {
        $response = $this->get('/');

        $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
    }

    public function test_x_frame_options_header_is_set()
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
}
