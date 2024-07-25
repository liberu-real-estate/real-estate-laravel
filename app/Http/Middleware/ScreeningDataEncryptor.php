<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Crypt;

class ScreeningDataEncryptor
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response->getContent()) {
            $content = json_decode($response->getContent(), true);

            if (isset($content['background_check_status'])) {
                $content['background_check_status'] = Crypt::encryptString($content['background_check_status']);
            }

            if (isset($content['credit_report_status'])) {
                $content['credit_report_status'] = Crypt::encryptString($content['credit_report_status']);
            }

            $response->setContent(json_encode($content));
        }

        return $response;
    }
}