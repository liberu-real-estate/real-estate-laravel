# Community Events Calendar

This feature allows properties to display nearby community events on their detail pages, helping potential buyers or renters understand the local community activities.

## Features

- **Event Calendar Display**: Shows upcoming community events near properties
- **Location-Based Filtering**: Events are filtered based on proximity to property (default 10km radius)
- **Event Categories**: Support for various event types (festivals, markets, sports, concerts, workshops, etc.)
- **Public/Private Events**: Events can be marked as public or private
- **Property Association**: Events can be associated with specific properties

## Database Schema

The `community_events` table includes:
- Basic event info (title, description, dates)
- Location data (address, latitude, longitude)
- Contact information (organizer, email, phone, website)
- Category and visibility settings
- Optional property association

## API Endpoints

### List Community Events
```
GET /api/community-events
```

Query Parameters:
- `category` - Filter by event category
- `latitude` & `longitude` - Filter by location
- `radius` - Search radius in km (default: 10)
- `property_id` - Get events near a specific property

### Get Single Event
```
GET /api/community-events/{id}
```

### Get Events for Property
```
GET /api/properties/{propertyId}/community-events
```

Query Parameters:
- `radius` - Search radius in km (default: 10)

## Models and Relationships

### CommunityEvent Model

**Scopes:**
- `upcoming()` - Only future events
- `public()` - Only public events
- `category($category)` - Filter by category
- `nearby($lat, $lng, $radius)` - Events within radius
- `forMonth($year, $month)` - Events in specific month

**Relationships:**
- `belongsTo(Property::class)` - Optional property association

### Property Model

**New Methods:**
- `communityEvents()` - Get associated events
- `getNearbyCommunityEvents($radius = 10)` - Get public events within radius

## Livewire Component

The `PropertyDetail` component has been enhanced with:

**Properties:**
- `$communityEvents` - Collection of nearby events
- `$selectedMonth` - Current calendar month
- `$selectedYear` - Current calendar year

**Methods:**
- `loadCommunityEvents()` - Load events for property
- `changeMonth($direction)` - Navigate calendar months
- `getEventsForCalendar()` - Get events for current month

## View Integration

Events are displayed on property detail pages with:
- Monthly calendar navigation
- Event cards showing key information
- Distance from property
- Category badges
- Contact/organizer information
- External links to event websites

## Testing

Run the test suite:
```bash
php artisan test --filter=CommunityEventTest
```

## Seeding Sample Data

To populate sample community events:

```bash
php artisan migrate
php artisan db:seed --class=CommunityEventSeeder
```

This will create:
- 20 general community events
- 2 events for each of the first 5 properties with coordinates

## Usage Example

```php
// Get upcoming events near a property
$property = Property::find(1);
$events = $property->getNearbyCommunityEvents(10); // 10km radius

// Create a new event
CommunityEvent::create([
    'title' => 'Summer Festival',
    'description' => 'Annual community summer festival',
    'event_date' => now()->addDays(30),
    'location' => '123 Main St',
    'latitude' => 51.5074,
    'longitude' => -0.1278,
    'category' => 'festival',
    'is_public' => true,
]);

// Filter events by category
$festivals = CommunityEvent::public()
    ->upcoming()
    ->category('festival')
    ->get();
```

## Future Enhancements

Potential improvements:
- Event registration/RSVP functionality
- Calendar export (iCal format)
- Email notifications for new events
- User-submitted events (with moderation)
- Integration with external event APIs
- Map view of events
- Recurring events support
