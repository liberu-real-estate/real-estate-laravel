<?php

namespace App\Exceptions;

use Closure;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
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
            if ($e instanceof OnTheMarketApiException) {
                Log::error('OnTheMarket API Error: ' . $e->getMessage());
            }
            if ($e instanceof Closure) {
                Log::error('Closure Error: ' . $e->getMessage());
            }
            if ($e instanceof QueryException) {
                Log::error('Database Query Error: ' . $e->getMessage());
            }
        });
    
        $this->renderable(function (Exception $e, $request) {
            if ($e instanceof Closure) {
                return response()->view('errors.500', [], 500);
            }
        });
    }
}
