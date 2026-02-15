# Neighborhood Information Feature

## Overview
The neighborhood information feature displays detailed information about the area surrounding each property on the property detail pages. This includes demographics, schools, amenities, and walkability scores.

## Components

### 1. Database Structure

#### Neighborhoods Table
- `name` - Neighborhood name
- `description` - Description of the neighborhood
- `schools` - JSON array of schools with ratings
- `amenities` - JSON array of available amenities
- `crime_rate` - Crime rate classification
- `median_income` - Median household income
- `population` - Population count
- `walk_score` - Walkability score (0-100)
- `transit_score` - Public transit score (0-100)
- `last_updated` - Timestamp of last data update

#### Properties Table
- `neighborhood_id` - Foreign key to neighborhoods table
- `postal_code` - Used for API lookups

### 2. Models

#### Neighborhood Model
Located at: `app/Models/Neighborhood.php`

**Relationships:**
- `hasMany` relationship with Property model

**Casts:**
- `schools` → array
- `amenities` → array
- `last_updated` → datetime

#### Property Model
Located at: `app/Models/Property.php`

**Relationships:**
- `belongsTo` relationship with Neighborhood model

### 3. Services

#### NeighborhoodDataService
Located at: `app/Services/NeighborhoodDataService.php`

**Purpose:** Fetches neighborhood data from external API

**Configuration:**
- `NEIGHBORHOOD_DATA_BASE_URI` - API base URL
- `NEIGHBORHOOD_DATA_API_KEY` - API authentication key

**Method:**
- `getNeighborhoodData($zipCode)` - Fetches neighborhood data for a given zip code

### 4. Livewire Component

#### PropertyDetail Component
Located at: `app/Http/Livewire/PropertyDetail.php`

**Features:**
- Displays neighborhood information on property detail pages
- Updates neighborhood data from API when property is viewed
- Handles missing neighborhood data gracefully

**Key Methods:**
- `mount($propertyId)` - Loads property with neighborhood data
- `updateNeighborhoodData()` - Refreshes neighborhood statistics from API

### 5. Views

#### Property Detail View
Located at: `resources/views/livewire/property-detail.blade.php`

**Displays:**
- Neighborhood overview and description
- Key statistics (population, median income, walk score, transit score)
- Schools with ratings
- Available amenities
- Last updated timestamp

## Configuration

### Environment Variables
Add to `.env` file:
```
NEIGHBORHOOD_DATA_BASE_URI=https://api.example.com
NEIGHBORHOOD_DATA_API_KEY=your-api-key-here
```

### Service Configuration
Configuration is located in `config/services.php`:
```php
'neighborhood_data' => [
    'base_uri' => env('NEIGHBORHOOD_DATA_BASE_URI', 'https://api.example.com'),
    'api_key' => env('NEIGHBORHOOD_DATA_API_KEY'),
],
```

## Database Setup

### Running Migrations
```bash
php artisan migrate
```

This will create the `neighborhoods` table with all required fields.

### Seeding Sample Data
```bash
php artisan db:seed --class=NeighborhoodSeeder
```

This creates 5 sample neighborhoods:
- Downtown District
- Suburban Hills
- Riverside
- Tech Quarter
- Old Town

### Seeding Properties with Neighborhoods
```bash
php artisan db:seed --class=PropertySeeder
```

This creates properties and automatically assigns them to random neighborhoods.

## Usage

### Assigning a Neighborhood to a Property
```php
$property = Property::find($id);
$property->neighborhood_id = $neighborhoodId;
$property->save();
```

### Accessing Neighborhood Data
```php
$property = Property::with('neighborhood')->find($id);
$neighborhood = $property->neighborhood;

echo $neighborhood->name;
echo $neighborhood->walk_score;
```

### Manual API Data Update
```php
$service = app(NeighborhoodDataService::class);
$data = $service->getNeighborhoodData($property->postal_code);

if ($data) {
    $neighborhood->update([
        'median_income' => $data['median_income'],
        'population' => $data['population'],
        'walk_score' => $data['walk_score'],
        'transit_score' => $data['transit_score'],
        'last_updated' => now(),
    ]);
}
```

## Testing

### Unit Tests
Located in `tests/Unit/`:
- `NeighborhoodTest.php` - Tests neighborhood model and relationships
- `NeighborhoodDataServiceTest.php` - Tests API service integration

### Running Tests
```bash
php artisan test tests/Unit/NeighborhoodTest.php
php artisan test tests/Unit/NeighborhoodDataServiceTest.php
```

## API Integration

The neighborhood data service integrates with an external API to fetch real-time neighborhood statistics. The API should return JSON in the following format:

```json
{
    "median_income": 85000,
    "population": 35000,
    "walk_score": 95,
    "transit_score": 90
}
```

## Error Handling

- If the API call fails, the service returns `null` and logs the error
- The UI gracefully handles missing neighborhood data with a fallback message
- Properties without assigned neighborhoods display "No neighborhood details available"

## Future Enhancements

Potential improvements:
1. Automated scheduled updates of neighborhood data
2. Neighborhood comparison tool
3. Interactive maps showing neighborhood boundaries
4. Crime statistics visualization
5. School ratings with links to detailed information
6. Nearby amenities with distance calculations
7. Historical trend data for neighborhood statistics
