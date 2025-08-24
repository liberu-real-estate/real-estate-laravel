<?php

namespace App\Exceptions;

use Log;
use Exception;

class PropertySyncException extends Exception
{
    protected $propertyId;
    protected $service;

    public function __construct($message, $propertyId, $service, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->propertyId = $propertyId;
        $this->service = $service;
    }

    public function getPropertyId()
    {
        return $this->propertyId;
    }

    public function getService()
    {
        return $this->service;
    }

    public function report()
    {
        Log::error("Property Sync Error: {$this->getMessage()}", [
            'property_id' => $this->getPropertyId(),
            'service' => $this->getService(),
            'exception' => get_class($this),
        ]);
    }
}