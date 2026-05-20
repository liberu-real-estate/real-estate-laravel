<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyHistory;
use Illuminate\Database\Seeder;

class PropertyHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing properties
        $properties = Property::take(5)->get();

        foreach ($properties as $property) {
            // Add listing event (initial listing)
            PropertyHistory::create([
                'property_id' => $property->id,
                'event_type' => 'listing',
                'description' => sprintf('Property listed for %s', number_format($property->price, 2)),
                'new_price' => $property->price,
                'event_date' => now()->subMonths(6),
                'user_id' => $property->user_id,
            ]);

            // Add a price reduction (if property is still available)
            if ($property->status !== 'sold') {
                $oldPrice = $property->price;
                $newPrice = $property->price * 0.95; // 5% reduction

                PropertyHistory::create([
                    'property_id' => $property->id,
                    'event_type' => 'price_change',
                    'description' => sprintf(
                        'Price decreased from %s to %s (%.2f%%)',
                        number_format($oldPrice, 2),
                        number_format($newPrice, 2),
                        -5.0
                    ),
                    'old_price' => $oldPrice,
                    'new_price' => $newPrice,
                    'event_date' => now()->subMonths(3),
                    'user_id' => $property->user_id,
                    'changes' => [
                        'field' => 'price',
                        'old_value' => $oldPrice,
                        'new_value' => $newPrice,
                        'percentage_change' => -5.0,
                    ],
                ]);

                // Add another price increase
                $oldPrice = $newPrice;
                $newPrice = $newPrice * 1.02; // 2% increase

                PropertyHistory::create([
                    'property_id' => $property->id,
                    'event_type' => 'price_change',
                    'description' => sprintf(
                        'Price increased from %s to %s (%.2f%%)',
                        number_format($oldPrice, 2),
                        number_format($newPrice, 2),
                        2.0
                    ),
                    'old_price' => $oldPrice,
                    'new_price' => $newPrice,
                    'event_date' => now()->subMonths(1),
                    'user_id' => $property->user_id,
                    'changes' => [
                        'field' => 'price',
                        'old_value' => $oldPrice,
                        'new_value' => $newPrice,
                        'percentage_change' => 2.0,
                    ],
                ]);
            }

            // Add status change
            if ($property->status === 'sold') {
                PropertyHistory::create([
                    'property_id' => $property->id,
                    'event_type' => 'status_change',
                    'description' => 'Status changed from available to sold',
                    'old_status' => 'available',
                    'new_status' => 'sold',
                    'event_date' => now()->subWeeks(2),
                    'user_id' => $property->user_id,
                    'changes' => [
                        'field' => 'status',
                        'old_value' => 'available',
                        'new_value' => 'sold',
                    ],
                ]);

                // Add sale event
                PropertyHistory::create([
                    'property_id' => $property->id,
                    'event_type' => 'sale',
                    'description' => sprintf('Property sold for %s', number_format($property->price, 2)),
                    'new_price' => $property->price,
                    'event_date' => now()->subWeeks(2),
                    'user_id' => $property->user_id,
                    'changes' => [
                        'field' => 'sold_date',
                        'sale_price' => $property->price,
                    ],
                ]);
            }

            // Add some update events
            PropertyHistory::create([
                'property_id' => $property->id,
                'event_type' => 'update',
                'description' => 'Updated: description, bedrooms',
                'event_date' => now()->subMonths(4),
                'user_id' => $property->user_id,
                'changes' => [
                    'description' => 'Updated property description',
                    'bedrooms' => $property->bedrooms,
                ],
            ]);
        }
    }
}
