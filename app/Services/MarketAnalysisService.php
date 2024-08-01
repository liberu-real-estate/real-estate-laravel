<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\DB;

class MarketAnalysisService
{
    public function generateMarketAnalysis($startDate, $endDate, $propertyIds = null)
    {
        $query = Property::query()
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($propertyIds) {
            $query->whereIn('id', $propertyIds);
        }

        $marketData = $query->select(
            DB::raw('AVG(price) as avg_price'),
            DB::raw('MIN(price) as min_price'),
            DB::raw('MAX(price) as max_price'),
            DB::raw('AVG(area_sqft) as avg_area'),
            DB::raw('COUNT(*) as total_properties'),
            'property_type'
        )
        ->groupBy('property_type')
        ->get();

        $pricePerSqFt = $query->select(
            DB::raw('AVG(price / area_sqft) as avg_price_per_sqft'),
            'property_type'
        )
        ->groupBy('property_type')
        ->get();

        return [
            'market_data' => $marketData,
            'price_per_sqft' => $pricePerSqFt,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }

    public function getMarketTrends($startDate, $endDate, $propertyIds = null)
    {
        $query = Property::query()
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($propertyIds) {
            $query->whereIn('id', $propertyIds);
        }

        $trends = $query->select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('AVG(price) as avg_price'),
            'property_type'
        )
        ->groupBy('month', 'property_type')
        ->orderBy('month')
        ->get();

        return $trends;
    }
}