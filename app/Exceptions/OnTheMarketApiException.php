<?php

namespace App\Exceptions;

use Exception;

class OnTheMarketApiException extends Exception
{
    protected $details;

    public function __construct($message, $code = 0, $details = null, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
