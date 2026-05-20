# Property History Tracking Feature

This feature provides comprehensive tracking of property changes and historical data in the real estate application.

## Overview

The Property History Tracking feature automatically records and displays:
- Price changes over time
- Status changes (available → sold, etc.)
- Past sales data
- Property listings
- General property updates

## Components

### Database

#### PropertyHistory Model (`app/Models/PropertyHistory.php`)
Tracks individual historical events for properties.

**Fields:**
- `property_id` - Reference to the property
- `event_type` - Type of event (price_change, status_change, sale, listing, update)
- `description` - Human-readable description
- `old_price/new_price` - For price-related events
- `old_status/new_status` - For status changes
- `changes` - JSON field for additional change data
- `event_date` - When the event occurred
- `user_id` - User who made the change

**Methods:**
- `getPriceChangePercentage()` - Calculate percentage change for price events
- `getFormattedDescription()` - Get formatted event description

**Scopes:**
- `byType($type)` - Filter by event type
- `priceChanges()` - Get price change events only
- `sales()` - Get sale events only

#### Migration
Creates the `property_histories` table with proper indexes for performance.

### Services

#### PropertyHistoryService (`app/Services/PropertyHistoryService.php`)
Central service for managing property history.

**Key Methods:**

```php
// Record a generic event
recordEvent(Property $property, string $eventType, string $description, array $additionalData = []): PropertyHistory

// Track price changes
trackPriceChange(Property $property, float $oldPrice, float $newPrice): PropertyHistory

// Track status changes
trackStatusChange(Property $property, string $oldStatus, string $newStatus): PropertyHistory

// Track sales
trackSale(Property $property, float $salePrice, ?\DateTime $saleDate = null): PropertyHistory

// Track listings
trackListing(Property $property, float $listingPrice): PropertyHistory

// Track general updates
trackUpdate(Property $property, array $changes, string $description = null): PropertyHistory

// Get history with filters
getHistory(Property $property, ?string $eventType = null, ?int $limit = null)
getPriceHistory(Property $property, ?int $limit = null)
getSalesHistory(Property $property, ?int $limit = null)
```

### Automatic Tracking

#### PropertyObserver (`app/Observers/PropertyObserver.php`)
Automatically tracks changes when properties are updated.

**Tracked Changes:**
- Price changes
- Status changes
- When property is sold (sold_date set)
- When property is listed (list_date set)

The observer is registered in `AppServiceProvider`.

## Frontend Display

### PropertyDetail Component (`app/Http/Livewire/PropertyDetail.php`)
Loads property history data including:
- `$propertyHistory` - Recent events (last 10)
- `$priceHistory` - Price changes (last 5)
- `$salesHistory` - All sales

### Property Detail View
Displays three sections:

1. **Price Changes**
   - Shows old price → new price
   - Displays percentage change with color coding (green for increase, red for decrease)
   - Includes event date

2. **Past Sales**
   - Lists previous sales with dates and prices
   - Highlights sale price

3. **Activity Timeline**
   - Shows all events in chronological order
   - Visual icons for different event types
   - Full event descriptions

## Usage Examples

### Manual Tracking

```php
use App\Models\Property;
use App\Services\PropertyHistoryService;

$property = Property::find(1);
$historyService = new PropertyHistoryService();

// Track a price change
$historyService->trackPriceChange($property, 250000, 275000);

// Track a sale
$historyService->trackSale($property, 265000);

// Track a status change
$historyService->trackStatusChange($property, 'available', 'sold');

// Track custom update
$historyService->trackUpdate($property, [
    'bedrooms' => 3,
    'bathrooms' => 2
], 'Updated room counts');
```

### Automatic Tracking

Simply update the property - changes are tracked automatically:

```php
$property = Property::find(1);

// This will automatically create a price_change history entry
$property->update(['price' => 280000]);

// This will automatically create a status_change history entry
$property->update(['status' => 'sold']);
```

### Retrieving History

```php
// Get all history
$history = $property->histories;

// Get price changes only
$priceHistory = $property->histories()->priceChanges()->get();

// Get sales history
$salesHistory = $property->histories()->sales()->get();

// Using service
$historyService = new PropertyHistoryService();
$recentHistory = $historyService->getHistory($property, null, 10);
$priceChanges = $historyService->getPriceHistory($property, 5);
```

## Testing

### Unit Tests (`tests/Unit/PropertyHistoryTest.php`)
Comprehensive test coverage including:
- Model creation and relationships
- Service methods
- Automatic tracking
- Scopes and filters
- Percentage calculations

Run tests:
```bash
php artisan test --filter=PropertyHistoryTest
```

### Factory (`database/factories/PropertyHistoryFactory.php`)
Generate test data:

```php
use App\Models\PropertyHistory;

// Create random history entry
PropertyHistory::factory()->create();

// Create price change
PropertyHistory::factory()->priceChange(250000, 275000)->create();

// Create sale
PropertyHistory::factory()->sale(265000)->create();
```

### Seeder (`database/seeders/PropertyHistorySeeder.php`)
Populate sample historical data:

```bash
php artisan db:seed --class=PropertyHistorySeeder
```

## Database Migration

Run the migration:
```bash
php artisan migrate
```

The migration creates the `property_histories` table with indexes for optimal query performance.

## Event Types

- `price_change` - Price was changed
- `status_change` - Status was changed
- `sale` - Property was sold
- `listing` - Property was listed
- `update` - General property update

## Performance Considerations

- Indexes on `property_id`, `event_date`, and `event_type`
- History queries are limited by default
- Observer uses cache to store temporary data between events
- History is ordered by `event_date DESC` for efficiency

## Future Enhancements

Possible future improvements:
- Export history to PDF/CSV
- Compare price trends across similar properties
- Notifications for significant price changes
- Analytics dashboard for market trends
- Integration with property valuation system
