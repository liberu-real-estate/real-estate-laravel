# AR Property Tours - Implementation Guide

## Overview

The AR Property Tours feature allows users to experience property listings in augmented reality directly from their mobile devices. This immersive feature enhances the property viewing experience by allowing potential buyers or renters to visualize properties in their own space.

## Features

### User-Facing Features
- **Interactive 3D Viewing**: Rotate, zoom, and explore properties in 3D
- **Augmented Reality Mode**: View properties overlaid in your real-world environment
- **Mobile-Optimized**: Seamless experience on supported iOS and Android devices
- **Multiple AR Modes**: Support for WebXR, Scene Viewer (Android), and Quick Look (iOS)
- **Intuitive Controls**: Easy-to-use gestures for navigation and scaling
- **Placement Guides**: Smart suggestions for where to place models in AR

### Admin Features
- **AR Tour Management**: Enable/disable AR tours per property
- **Configuration Options**: Customize AR experience settings
- **Model Scale Control**: Adjust model size for optimal viewing
- **Placement Guide Settings**: Configure default placement (floor, wall, ceiling)

## Technology Stack

### Frontend
- **Google Model Viewer**: Industry-standard web component for 3D models
- **AR Support**: Built-in support for ARCore (Android) and ARKit (iOS)
- **3D Formats**: GLB and GLTF file formats

### Backend
- **Laravel Framework**: RESTful API endpoints for AR configuration
- **Spatie Media Library**: 3D model file management
- **Database**: AR tour settings and metadata storage

## Setup Instructions

### 1. Database Migration

Run the migration to add AR tour fields to the properties table:

```bash
php artisan migrate
```

This adds the following fields:
- `ar_tour_enabled`: Boolean flag to enable/disable AR tours
- `ar_tour_settings`: JSON field for AR configuration
- `ar_placement_guide`: Suggested placement location (floor/wall/ceiling)
- `ar_model_scale`: Scale factor for the 3D model in AR

### 2. Frontend Dependencies

The required `@google/model-viewer` package is already installed via npm. Ensure it's properly loaded:

```bash
npm install
npm run build
```

### 3. Enable AR for a Property

#### Via Admin Panel (Filament)

1. Navigate to Properties management
2. Edit a property that has a 3D model uploaded
3. Toggle "Enable AR Tour" to ON
4. Configure AR settings:
   - **AR Model Scale**: Adjust size (default: 1.0)
   - **AR Placement Guide**: Choose placement type (floor/wall/ceiling)
5. Save the property

#### Via API

Enable AR tour programmatically:

```php
use App\Services\ARTourService;

$arTourService = app(ARTourService::class);
$arTourService->enableARTour($property, [
    'auto_rotate' => true,
    'shadow_intensity' => 1,
    'ar_modes' => ['webxr', 'scene-viewer', 'quick-look']
]);
```

### 4. Upload 3D Models

#### Requirements
- **Format**: GLB or GLTF
- **Recommended Size**: Under 10MB for optimal mobile performance
- **Optimization**: Use compressed textures and simplified geometry

#### Via Admin Panel
1. Edit a property
2. Upload a 3D model file in the "3D Model" field
3. The system automatically validates the format

#### Best Practices for 3D Models
- Use GLB format for better compression
- Optimize polygon count (aim for under 50k polygons)
- Use power-of-two texture sizes (512x512, 1024x1024, etc.)
- Bake lighting when possible to reduce real-time calculations

## API Endpoints

### Get AR Tour Configuration

```
GET /properties/{property}/ar-tour/config
```

Response:
```json
{
  "available": true,
  "config": {
    "model_url": "https://example.com/models/property.glb",
    "scale": 1.0,
    "placement_guide": "floor",
    "ar_modes": ["webxr", "scene-viewer", "quick-look"],
    "enable_controls": true,
    "auto_rotate": true,
    "shadow_intensity": 1
  },
  "property": {
    "id": 1,
    "title": "Modern Downtown Apartment",
    "location": "123 Main St"
  }
}
```

### Check AR Tour Availability

```
GET /properties/{property}/ar-tour/availability
```

### Enable AR Tour (Authenticated)

```
POST /properties/{property}/ar-tour/enable
```

Body:
```json
{
  "ar_model_scale": 1.5,
  "auto_rotate": true,
  "shadow_intensity": 1
}
```

### Disable AR Tour (Authenticated)

```
POST /properties/{property}/ar-tour/disable
```

### Update AR Settings (Authenticated)

```
PUT /properties/{property}/ar-tour/settings
```

Body:
```json
{
  "auto_rotate": false,
  "shadow_intensity": 2,
  "ar_model_scale": 2.5,
  "ar_placement_guide": "floor"
}
```

## User Experience

### Desktop/Tablet
1. User navigates to property detail page
2. 3D model viewer is displayed (if available)
3. User can rotate, zoom, and explore the model
4. AR button is visible for mobile devices

### Mobile Devices (with AR support)

#### iOS (ARKit)
1. User taps the AR button in the model viewer
2. Quick Look opens with the 3D model
3. User points camera at a flat surface
4. Model appears in the real world
5. User can walk around, scale, and interact

#### Android (ARCore)
1. User taps the AR button
2. Scene Viewer launches
3. User points camera at a surface
4. Model is placed in augmented reality
5. User can move, rotate, and scale

### Supported Devices

#### iOS
- iPhone 6s and later
- iPad (5th generation) and later
- iOS 12 or later

#### Android
- Devices with ARCore support
- Android 7.0 or later
- Check [ARCore supported devices](https://developers.google.com/ar/devices)

## Configuration Options

### Available Settings

```php
[
    'ar_modes' => ['webxr', 'scene-viewer', 'quick-look'],
    'enable_controls' => true,
    'auto_rotate' => true,
    'shadow_intensity' => 1,
    'camera_orbit' => '0deg 75deg 2.5m',
    'min_camera_orbit' => 'auto auto 1m',
    'max_camera_orbit' => 'auto auto 10m',
    'interaction_prompt' => 'auto',
]
```

### Setting Descriptions

- **ar_modes**: Enabled AR platforms
- **enable_controls**: Allow user camera control
- **auto_rotate**: Automatically rotate model
- **shadow_intensity**: Shadow darkness (0-2)
- **camera_orbit**: Initial camera position
- **min_camera_orbit**: Minimum zoom distance
- **max_camera_orbit**: Maximum zoom distance
- **interaction_prompt**: When to show interaction hints

## Testing

### Run Unit Tests

```bash
php artisan test tests/Unit/ARTourTest.php
```

### Run Feature Tests

```bash
php artisan test tests/Feature/ARTourControllerTest.php
```

### Manual Testing Checklist

- [ ] Upload a 3D model to a property
- [ ] Enable AR tour via admin panel
- [ ] View property detail page on desktop
- [ ] Verify 3D model displays correctly
- [ ] Test on mobile device with AR support
- [ ] Tap AR button and verify AR experience
- [ ] Test scaling and rotation in AR
- [ ] Verify AR instructions are displayed
- [ ] Test with different placement guides
- [ ] Verify AR tour availability indicator

## Troubleshooting

### AR Button Not Appearing
- Ensure property has `ar_tour_enabled = true`
- Verify 3D model is uploaded and valid
- Check that device supports AR

### Model Not Loading in AR
- Verify GLB/GLTF file format
- Check file size (should be under 10MB)
- Ensure proper MIME type is set
- Test model in standalone viewer first

### Performance Issues
- Reduce polygon count in 3D model
- Compress textures
- Optimize model with tools like glTF-Pipeline
- Consider using Draco compression

### Browser Compatibility
- Chrome 79+ (Android)
- Safari 13+ (iOS)
- Edge 79+
- Firefox 70+

## Future Enhancements

### Planned Features
- [ ] AR tour analytics (view duration, interactions)
- [ ] Multiple 3D models per property (rooms, exterior)
- [ ] Guided AR tours with annotations
- [ ] AR measurement tools
- [ ] Furniture placement in AR
- [ ] Social sharing of AR experiences
- [ ] VR headset support

### Integration Opportunities
- Integration with virtual staging
- AI-powered furniture recommendations
- Real-time collaboration in AR
- AR property comparison tool

## Support

For issues or questions:
- Check the troubleshooting section above
- Review API documentation
- Contact development team

## Resources

### External Documentation
- [Google Model Viewer Documentation](https://modelviewer.dev/)
- [ARCore Overview](https://developers.google.com/ar)
- [ARKit Documentation](https://developer.apple.com/augmented-reality/)
- [glTF Format Specification](https://www.khronos.org/gltf/)

### Tools
- [glTF Viewer](https://gltf-viewer.donmccurdy.com/)
- [Blender](https://www.blender.org/) - 3D modeling and export
- [glTF-Pipeline](https://github.com/CesiumGS/gltf-pipeline) - Optimization
