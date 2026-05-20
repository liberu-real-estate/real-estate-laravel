<?php

namespace App\Services;

use App\Models\PriceAlert;
use App\Models\Property;
use App\Notifications\PriceAlertNotification;

class PriceAlertService
{
    public function checkPriceAlerts()
    {
        $activeAlerts = PriceAlert::where('is_active', true)->get();

        foreach ($activeAlerts as $alert) {
            $property = $alert->property;
            $priceDifference = $this->calculatePriceDifference($alert->initial_price, $property->price);

            if (abs($priceDifference) >= $alert->alert_percentage) {
                $this->sendPriceAlertNotification($alert, $property, $priceDifference);
                $this->updateAlertInitialPrice($alert, $property->price);
            }
        }
    }

    private function calculatePriceDifference($initialPrice, $currentPrice)
    {
        return (($currentPrice - $initialPrice) / $initialPrice) * 100;
    }

    private function sendPriceAlertNotification(PriceAlert $alert, Property $property, $priceDifference)
    {
        $alert->user->notify(new PriceAlertNotification($property, $priceDifference));
    }

    private function updateAlertInitialPrice(PriceAlert $alert, $newPrice)
    {
        $alert->update(['initial_price' => $newPrice]);
    }
}