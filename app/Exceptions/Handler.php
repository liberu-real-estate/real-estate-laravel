<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($e instanceof \App\Exceptions\OnTheMarketApiException) {
                Log::error('OnTheMarket API Error: ' . $e->getMessage());
            }
            if ($e instanceof \Closure) {
                Log::error('Closure Error: ' . $e->getMessage());
            }
            if ($e instanceof \Illuminate\Database\QueryException) {
                Log::error('Database Query Error: ' . $e->getMessage());
            }
        });
    
        $this->renderable(function (\Exception $e, $request) {
            if ($e instanceof \Closure) {
                return response()->view('errors.500', [], 500);
            }
        });
    }
}
