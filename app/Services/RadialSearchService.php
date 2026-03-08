<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class RadialSearchService
{
    private const EARTH_RADIUS_KM = 6371;
    private const EARTH_RADIUS_MILES = 3958.8;

    /**
     * Find properties within a given radius of a central point.
     *
     * @param  float  $latitude  Centre latitude
     * @param  float  $longitude  Centre longitude
     * @param  float  $radius  Search radius
     * @param  string  $unit  'km' or 'miles'
     * @param  array  $filters  Additional property filters (property_type, status, etc.)
     * @return Collection
     */
    public function findPropertiesWithinRadius(
        float $latitude,
        float $longitude,
        float $radius,
        string $unit = 'miles',
        array $filters = []
    ): Collection {
        if ($radius <= 0) {
            throw new InvalidArgumentException('Radius must be greater than zero.');
        }

        $earthRadius = $unit === 'km' ? self::EARTH_RADIUS_KM : self::EARTH_RADIUS_MILES;

        $haversine = "({$earthRadius} * acos(
                cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(latitude))
            ))";

        $query = Property::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("*, {$haversine} AS distance", [$latitude, $longitude, $latitude])
            ->whereRaw("{$haversine} <= ?", [$latitude, $longitude, $latitude, $radius])
            ->orderByRaw($haversine, [$latitude, $longitude, $latitude]);

        foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }

        return $query->get();
    }

    /**
     * Calculate the distance between two geographic coordinates.
     *
     * @param  float  $lat1
     * @param  float  $lon1
     * @param  float  $lat2
     * @param  float  $lon2
     * @param  string  $unit  'km' or 'miles'
     * @return float
     */
    public function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2,
        string $unit = 'miles'
    ): float {
        $earthRadius = $unit === 'km' ? self::EARTH_RADIUS_KM : self::EARTH_RADIUS_MILES;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 4);
    }

    /**
     * Find properties within a drawn polygon (draw-a-search).
     *
     * @param  array  $polygon  Array of ['lat' => float, 'lng' => float] coordinate pairs
     * @param  array  $filters  Additional property filters
     * @return Collection
     */
    public function findPropertiesWithinPolygon(array $polygon, array $filters = []): Collection
    {
        if (count($polygon) < 3) {
            throw new InvalidArgumentException('A polygon must have at least 3 vertices.');
        }

        $query = Property::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }

        $properties = $query->get();

        return $properties->filter(function (Property $property) use ($polygon) {
            return $this->pointInPolygon(
                (float) $property->latitude,
                (float) $property->longitude,
                $polygon
            );
        })->values();
    }

    /**
     * Ray-casting algorithm to determine if a point is inside a polygon.
     */
    private function pointInPolygon(float $lat, float $lng, array $polygon): bool
    {
        $inside = false;
        $n = count($polygon);

        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = $polygon[$i]['lat'];
            $yi = $polygon[$i]['lng'];
            $xj = $polygon[$j]['lat'];
            $yj = $polygon[$j]['lng'];

            $intersect = (($yi > $lng) !== ($yj > $lng))
                && ($lat < ($xj - $xi) * ($lng - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }
}
