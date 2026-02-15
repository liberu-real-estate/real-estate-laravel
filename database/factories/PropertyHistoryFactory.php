<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyHistoryFactory extends Factory
{
    protected $model = PropertyHistory::class;

    public function definition()
    {
        $eventTypes = ['price_change', 'status_change', 'sale', 'listing', 'update'];
        $eventType = $this->faker->randomElement($eventTypes);
        
        $definition = [
            'property_id' => Property::factory(),
            'event_type' => $eventType,
            'event_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'user_id' => User::factory(),
        ];

        switch ($eventType) {
            case 'price_change':
                $oldPrice = $this->faker->numberBetween(100000, 500000);
                $newPrice = $oldPrice * $this->faker->randomFloat(2, 0.9, 1.1);
                $percentage = (($newPrice - $oldPrice) / $oldPrice) * 100;
                
                $definition['description'] = sprintf(
                    'Price %s from %s to %s (%.2f%%)',
                    $percentage >= 0 ? 'increased' : 'decreased',
                    number_format($oldPrice, 2),
                    number_format($newPrice, 2),
                    abs($percentage)
                );
                $definition['old_price'] = $oldPrice;
                $definition['new_price'] = $newPrice;
                $definition['changes'] = [
                    'field' => 'price',
                    'old_value' => $oldPrice,
                    'new_value' => $newPrice,
                    'percentage_change' => $percentage,
                ];
                break;

            case 'status_change':
                $statuses = ['available', 'pending', 'sold', 'withdrawn'];
                $oldStatus = $this->faker->randomElement($statuses);
                $newStatus = $this->faker->randomElement(array_diff($statuses, [$oldStatus]));
                
                $definition['description'] = sprintf('Status changed from %s to %s', $oldStatus, $newStatus);
                $definition['old_status'] = $oldStatus;
                $definition['new_status'] = $newStatus;
                $definition['changes'] = [
                    'field' => 'status',
                    'old_value' => $oldStatus,
                    'new_value' => $newStatus,
                ];
                break;

            case 'sale':
                $salePrice = $this->faker->numberBetween(100000, 500000);
                $definition['description'] = sprintf('Property sold for %s', number_format($salePrice, 2));
                $definition['new_price'] = $salePrice;
                $definition['changes'] = [
                    'field' => 'sold_date',
                    'sale_price' => $salePrice,
                ];
                break;

            case 'listing':
                $listingPrice = $this->faker->numberBetween(100000, 500000);
                $definition['description'] = sprintf('Property listed for %s', number_format($listingPrice, 2));
                $definition['new_price'] = $listingPrice;
                $definition['changes'] = [
                    'field' => 'list_date',
                    'listing_price' => $listingPrice,
                ];
                break;

            case 'update':
                $fields = ['description', 'bedrooms', 'bathrooms', 'area_sqft'];
                $updatedFields = $this->faker->randomElements($fields, $this->faker->numberBetween(1, 3));
                $definition['description'] = sprintf('Updated: %s', implode(', ', $updatedFields));
                $definition['changes'] = array_fill_keys($updatedFields, 'updated');
                break;
        }

        return $definition;
    }

    /**
     * Indicate that the history is a price change.
     */
    public function priceChange($oldPrice = null, $newPrice = null)
    {
        return $this->state(function (array $attributes) use ($oldPrice, $newPrice) {
            $oldPrice = $oldPrice ?? $this->faker->numberBetween(100000, 500000);
            $newPrice = $newPrice ?? $oldPrice * $this->faker->randomFloat(2, 0.9, 1.1);
            $percentage = (($newPrice - $oldPrice) / $oldPrice) * 100;
            
            return [
                'event_type' => 'price_change',
                'description' => sprintf(
                    'Price %s from %s to %s (%.2f%%)',
                    $percentage >= 0 ? 'increased' : 'decreased',
                    number_format($oldPrice, 2),
                    number_format($newPrice, 2),
                    abs($percentage)
                ),
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'changes' => [
                    'field' => 'price',
                    'old_value' => $oldPrice,
                    'new_value' => $newPrice,
                    'percentage_change' => $percentage,
                ],
            ];
        });
    }

    /**
     * Indicate that the history is a sale.
     */
    public function sale($salePrice = null)
    {
        return $this->state(function (array $attributes) use ($salePrice) {
            $salePrice = $salePrice ?? $this->faker->numberBetween(100000, 500000);
            
            return [
                'event_type' => 'sale',
                'description' => sprintf('Property sold for %s', number_format($salePrice, 2)),
                'new_price' => $salePrice,
                'changes' => [
                    'field' => 'sold_date',
                    'sale_price' => $salePrice,
                ],
            ];
        });
    }
}
