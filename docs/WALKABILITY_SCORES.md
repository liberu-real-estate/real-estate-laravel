# Walkability Scores Feature

## Overview
This feature integrates walkability, transit, and bike scores into property details, providing valuable insights about a property's location and accessibility.

## Implementation Details

### Components Added

1. **WalkScoreService** (`app/Services/WalkScoreService.php`)
   - Fetches walkability scores from Walk Score API
   - Falls back to mock data when API key is not configured
   - Provides deterministic mock scores for development/testing

2. **Database Migration** (`database/migrations/2024_03_15_000000_add_walkability_scores_to_properties_table.php`)
   - Adds walkability-related fields to properties table:
     - `walkability_score`: Integer (0-100)
     - `walkability_description`: String description (e.g., "Very Walkable")
     - `transit_score`: Integer (0-100)
     - `transit_description`: String description
     - `bike_score`: Integer (0-100)
     - `bike_description`: String description
     - `walkability_updated_at`: Timestamp of last update

3. **Property Model Updates** (`app/Models/Property.php`)
   - Added fillable fields for walkability data
   - Added `updateWalkabilityScores()` method to fetch and update scores
   - Added `needsWalkabilityUpdate()` method to check if scores need refreshing (>30 days old)

4. **PropertyDetail Component Updates** (`app/Http/Livewire/PropertyDetail.php`)
   - Automatically fetches walkability scores when property details are loaded
   - Updates scores only if they're missing or outdated

5. **UI Updates** (`resources/views/livewire/property-detail.blade.php`)
   - Displays walkability scores with colorful gradient badges
   - Shows all three scores: Walk, Transit, and Bike
   - Includes descriptions and last updated timestamp

### Configuration

Add to your `.env` file:
```
WALKSCORE_API_KEY=your_api_key_here
WALKSCORE_BASE_URI=https://api.walkscore.com
```

**Note**: If `WALKSCORE_API_KEY` is not set, the service will use mock data for development.

### API Integration

The Walk Score API requires:
- API key (obtain from https://www.walkscore.com/professional/api.php)
- Property address
- Latitude and longitude coordinates

### Mock Data

When no API key is configured, the service generates deterministic mock scores based on the property's coordinates. This allows development and testing without API access.

### How It Works

1. When a user views a property detail page, the system checks if walkability scores exist
2. If scores are missing or older than 30 days, it fetches fresh data
3. The WalkScoreService calls the Walk Score API (or generates mock data)
4. Scores are saved to the database for future quick access
5. The UI displays the scores with color-coded badges and descriptions

### Score Interpretations

**Walk Score (0-100)**
- 90-100: Walker's Paradise (Daily errands do not require a car)
- 70-89: Very Walkable (Most errands can be accomplished on foot)
- 50-69: Somewhat Walkable (Some errands can be accomplished on foot)
- 25-49: Car-Dependent (Most errands require a car)
- 0-24: Very Car-Dependent (Almost all errands require a car)

**Transit Score (0-100)**
- 90-100: Rider's Paradise (World-class public transportation)
- 70-89: Excellent Transit (Convenient for most trips)
- 50-69: Good Transit (Many nearby public transportation options)
- 25-49: Some Transit (A few public transportation options)
- 0-24: Minimal Transit (Public transportation is minimal)

**Bike Score (0-100)**
- 90-100: Biker's Paradise (Daily errands can be accomplished on a bike)
- 70-89: Very Bikeable (Biking is convenient for most trips)
- 50-69: Bikeable (Some bike infrastructure)
- 0-49: Somewhat Bikeable (Minimal bike infrastructure)

### Testing

Tests are provided in:
- `tests/Unit/WalkScoreServiceTest.php` - Tests for the WalkScore service
- `tests/Unit/PropertyTest.php` - Tests for Property model walkability methods

Run tests with:
```bash
php artisan test --filter=WalkScore
php artisan test --filter=PropertyTest
```

### Visual Appearance

The walkability scores are displayed on the property detail page with:
- **Walk Score**: Purple gradient badge
- **Transit Score**: Pink/red gradient badge
- **Bike Score**: Blue gradient badge

Each score shows:
- Numeric score (0-100)
- Description (e.g., "Very Walkable")
- What it measures (e.g., "Daily errands and amenities")
- Last updated timestamp

### Future Enhancements

Possible improvements:
1. Bulk update command to refresh all property walkability scores
2. Admin panel to manage API settings
3. Display scores on property listing cards
4. Filter properties by minimum walkability score
5. Add neighborhood-level averages
