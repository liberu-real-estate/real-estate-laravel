# Virtual Tours Feature - Quick Start Guide

## Overview
The virtual tours feature allows properties to showcase immersive 3D tours and enables users to schedule live virtual tours with agents.

## For Users

### Viewing Virtual Tours
1. Navigate to any property detail page
2. If the property has a virtual tour, you'll see a "View Virtual Tour" button
3. Click the button to display the embedded 3D tour
4. Explore the property using your mouse/touch controls
5. Compatible with VR headsets for supported providers

### Scheduling Live Virtual Tours
1. On properties with live tours enabled, click "Schedule Live Tour"
2. Select your preferred date and time
3. Add any questions or areas of interest in the notes field
4. Submit the form to schedule your appointment
5. You'll receive a confirmation and the agent will contact you

## For Administrators

### Adding Virtual Tours to Properties

#### Via Admin Panel (Filament)
1. Navigate to Properties → Edit Property
2. Find the "Virtual Tour URL" field
3. Paste the tour URL from your provider (e.g., Matterport, Kuula)
4. Select the provider from the dropdown (optional)
5. Toggle "Live Virtual Tours Available" if you want to offer scheduled tours
6. Save the property

#### Supported Providers
- **Matterport** - `https://my.matterport.com/show/?m=XXXXX`
- **Kuula** - `https://kuula.co/share/XXXXX`
- **3D Vista** - `https://www.3dvista.com/tour/XXXXX`
- **Seekbeak** - `https://seekbeak.com/v/XXXXX`
- **Custom** - Any URL with iframe support

#### Custom Embed Codes
If auto-generation doesn't work for your provider:
1. Get the embed code from your virtual tour provider
2. Paste it in the "Virtual Tour Embed Code" field
3. Save the property

### Demo Data
Run the database seeder to create sample properties with virtual tours:
```bash
php artisan db:seed --class=PropertySeeder
```

This will create properties where:
- ~30% of regular properties have virtual tours
- ~50% of HMO properties have virtual tours
- ~60-70% of properties with tours offer live scheduling

## For Developers

### Property Model Methods

```php
// Check if property has a virtual tour
if ($property->hasVirtualTour()) {
    // Display virtual tour button
}

// Get the embed HTML
$embedHtml = $property->getVirtualTourEmbed();

// Check if live tours are available
if ($property->live_tour_available) {
    // Show schedule button
}
```

### Livewire Component Usage

```php
// In PropertyDetail component
wire:click="toggleVirtualTour"      // Toggle tour display
wire:click="openScheduleLiveTourModal"  // Open scheduling modal
wire:submit.prevent="scheduleLiveTour"  // Submit scheduling form
```

### Database Fields

```sql
-- Properties table
virtual_tour_url VARCHAR(255) NULL           -- URL to the tour
virtual_tour_provider VARCHAR(255) NULL      -- Provider name
virtual_tour_embed_code TEXT NULL            -- Custom embed code
live_tour_available BOOLEAN DEFAULT FALSE    -- Enable live tours
```

### Running Tests

```bash
# Run all virtual tour tests
php artisan test --filter VirtualTour

# Run specific test file
php artisan test tests/Feature/VirtualTourTest.php
php artisan test tests/Unit/PropertyTest.php --filter virtual
```

## API Integration (Future)

### REST Endpoints (Proposed)
```
GET /api/properties/{id}/virtual-tour
POST /api/properties/{id}/schedule-live-tour
GET /api/properties/{id}/appointments
```

## Troubleshooting

### Virtual Tour Not Displaying
1. Check that the URL is valid and accessible
2. Verify the provider allows iframe embedding
3. Check browser console for any errors
4. Try using custom embed code instead

### Schedule Button Not Showing
1. Verify `live_tour_available` is set to `true`
2. Check that the property has a virtual tour URL
3. Ensure user is authenticated

### Embed Code Security
All URLs are automatically sanitized using `htmlspecialchars()` with `ENT_QUOTES` to prevent XSS attacks.

## Best Practices

1. **Use High-Quality Tours**: Ensure tours are well-lit and comprehensive
2. **Test Before Publishing**: Preview tours in the admin panel
3. **Keep URLs Updated**: Monitor for broken or expired tour links
4. **Respond Promptly**: Answer live tour requests within 24 hours
5. **Mobile Optimization**: Test tours on mobile devices

## Resources

- [Full Documentation](VIRTUAL_TOURS_INTEGRATION.md)
- [Implementation Summary](VIRTUAL_TOURS_IMPLEMENTATION_SUMMARY.md)
- [Matterport Support](https://support.matterport.com/)
- [Kuula Documentation](https://kuula.co/support)

## Feature Roadmap

- [ ] Video call integration (Zoom/Teams)
- [ ] Tour analytics and heatmaps
- [ ] Multi-property tour packages
- [ ] VR headset optimization
- [ ] Automated tour reminders
- [ ] Tour recording and replay

---

**Version**: 1.0.0  
**Last Updated**: February 16, 2026  
**Status**: Production Ready ✅
