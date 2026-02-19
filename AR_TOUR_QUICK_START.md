# AR Property Tours - Quick Start Guide

## 🚀 Getting Started with AR Property Tours

This guide will help you quickly set up and use the AR Property Tours feature.

## Prerequisites

- Laravel application already set up
- Database configured
- Google Model Viewer npm package installed
- Property listings with images

## 5-Minute Setup

### Step 1: Run the Migration

```bash
php artisan migrate
```

This adds AR tour fields to the properties table:
- `ar_tour_enabled` (boolean)
- `ar_tour_settings` (json)
- `ar_placement_guide` (string)
- `ar_model_scale` (float)

### Step 2: Upload a 3D Model

1. Log into the admin panel (Filament)
2. Navigate to Properties
3. Edit a property
4. Upload a 3D model file (.glb or .gltf)
   - **Recommended**: Use GLB format
   - **File Size**: Under 10MB for best mobile performance
   - **Where to get models**: [Sketchfab](https://sketchfab.com/), [Poly](https://poly.pizza/), or create with Blender

### Step 3: Enable AR Tour

Still in the property edit form:
1. Toggle **"Enable AR Tour"** to ON
2. Set **AR Model Scale** (default: 1.0, range: 0.1-10)
   - 1.0 = original size
   - 0.5 = half size
   - 2.0 = double size
3. Choose **AR Placement Guide**:
   - **Floor** (recommended for buildings/houses)
   - **Wall** (for wall-mounted features)
   - **Ceiling** (for ceiling fixtures)
4. **Save** the property

### Step 4: View on Mobile

1. Open the property detail page on your mobile device
2. Look for the **"AR Available"** badge
3. Tap the **AR button** (cube icon) on the 3D model viewer
4. Point your camera at a flat surface
5. The property appears in augmented reality!

## Usage Examples

### For Administrators

#### Enable AR for Multiple Properties
```php
use App\Services\ARTourService;

$arTourService = app(ARTourService::class);

Property::whereHas('media', function($query) {
    $query->where('collection_name', '3d_models');
})->each(function($property) use ($arTourService) {
    $arTourService->enableARTour($property);
});
```

#### Update AR Settings
```php
$arTourService->updateARTourSettings($property, [
    'auto_rotate' => true,
    'shadow_intensity' => 1.5,
    'camera_orbit' => '0deg 90deg 3m'
]);
```

### For Developers

#### Check if AR is Available
```php
$isAvailable = $property->ar_tour_enabled && $property->hasMedia('3d_models');
```

#### Get AR Configuration via API
```bash
curl https://yoursite.com/properties/123/ar-tour/config
```

#### Enable AR via API (requires authentication)
```bash
curl -X POST https://yoursite.com/properties/123/ar-tour/enable \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"ar_model_scale": 1.5}'
```

## Common Scenarios

### Scenario 1: Add AR to Existing Property

```php
// Find property
$property = Property::find(1);

// Upload 3D model
$property->addMedia('path/to/model.glb')
    ->toMediaCollection('3d_models');

// Enable AR
$arTourService = app(ARTourService::class);
$arTourService->enableARTour($property, [
    'ar_model_scale' => 1.0,
    'ar_placement_guide' => 'floor'
]);
```

### Scenario 2: Bulk Enable AR

```php
use App\Services\ARTourService;

$arTourService = app(ARTourService::class);

// Enable AR for all properties with 3D models
Property::has('media')->each(function($property) use ($arTourService) {
    if ($property->hasMedia('3d_models')) {
        $arTourService->enableARTour($property);
    }
});
```

### Scenario 3: Custom AR Settings

```php
$property->update([
    'ar_tour_enabled' => true,
    'ar_model_scale' => 2.5,  // Larger display
    'ar_placement_guide' => 'floor',
    'ar_tour_settings' => [
        'auto_rotate' => true,
        'shadow_intensity' => 2,
        'camera_orbit' => '45deg 75deg 2.5m',
        'interaction_prompt' => 'auto'
    ]
]);
```

## Testing Your AR Tour

### Desktop Testing
1. Open property detail page
2. Verify 3D model loads
3. Check for "AR Available" badge
4. Test rotation and zoom controls

### Mobile Testing (iOS)
1. Open Safari on iPhone/iPad
2. Navigate to property page
3. Tap AR button
4. Point at floor/surface
5. Model should appear in AR

### Mobile Testing (Android)
1. Open Chrome on Android device
2. Navigate to property page  
3. Tap AR button
4. Point at floor/surface
5. Model should appear in AR

## Troubleshooting

### AR Button Not Showing
- ✅ Check `ar_tour_enabled` is true
- ✅ Verify 3D model is uploaded
- ✅ Confirm device supports AR
- ✅ Check browser compatibility

### Model Not Loading
- ✅ Verify file format (GLB or GLTF only)
- ✅ Check file size (under 10MB recommended)
- ✅ Validate 3D model file integrity
- ✅ Check browser console for errors

### AR Experience Laggy
- ✅ Reduce 3D model polygon count
- ✅ Compress textures
- ✅ Use smaller file size
- ✅ Reduce ar_model_scale

### Device Not Supported
- **iOS**: Requires iPhone 6s+ with iOS 12+
- **Android**: Requires ARCore-compatible device with Android 7.0+
- **Check**: [ARCore devices](https://developers.google.com/ar/devices)

## Best Practices

### 3D Model Optimization
- **Format**: Use GLB (compressed) over GLTF
- **Polygons**: Keep under 50,000 triangles
- **Textures**: Use power-of-two sizes (512, 1024, 2048)
- **File Size**: Target 2-5MB for best performance
- **Tools**: Use [glTF-Pipeline](https://github.com/CesiumGS/gltf-pipeline) for optimization

### AR Settings
- **Scale**: Start with 1.0, adjust based on property type
  - Small items (furniture): 0.5-1.0
  - Rooms: 1.0-2.0
  - Buildings: 2.0-5.0
- **Placement**: Use "floor" for most property types
- **Auto-rotate**: Enable for showcase effect
- **Shadow intensity**: 1.0 for realistic shadows

### User Experience
- ✅ Add clear instructions on property page
- ✅ Show "AR Available" badge prominently
- ✅ Provide fallback for non-AR devices
- ✅ Test on multiple devices before launch
- ✅ Monitor performance metrics

## API Endpoints Quick Reference

```
Public Endpoints:
GET  /properties/{id}/ar-tour/config        - Get AR configuration
GET  /properties/{id}/ar-tour/availability  - Check if AR available

Authenticated Endpoints (require login):
POST /properties/{id}/ar-tour/enable        - Enable AR tour
POST /properties/{id}/ar-tour/disable       - Disable AR tour
PUT  /properties/{id}/ar-tour/settings      - Update AR settings
```

## Where to Get 3D Models

### Free Resources
- [Sketchfab](https://sketchfab.com/) - Search "house", "building", "apartment"
- [Poly Pizza](https://poly.pizza/) - Google Poly alternatives
- [Free3D](https://free3d.com/) - Architecture section

### Paid Resources
- [TurboSquid](https://www.turbosquid.com/) - Professional models
- [CGTrader](https://www.cgtrader.com/) - Large marketplace
- [Envato Elements](https://elements.envato.com/3d-models) - Subscription-based

### Creating Your Own
- [Blender](https://www.blender.org/) - Free 3D modeling software
- [SketchUp](https://www.sketchup.com/) - Easy architectural modeling
- [Tinkercad](https://www.tinkercad.com/) - Simple online modeler

## Next Steps

### Learn More
- 📖 Read [AR_TOUR_IMPLEMENTATION.md](./AR_TOUR_IMPLEMENTATION.md) for detailed documentation
- 🏗️ Review [AR_TOUR_ARCHITECTURE.md](./AR_TOUR_ARCHITECTURE.md) for system architecture
- 📊 Check [AR_TOUR_FINAL_SUMMARY.md](./AR_TOUR_FINAL_SUMMARY.md) for complete implementation details

### Advanced Features
- Implement AR tour analytics
- Add multiple 3D models per property
- Create guided AR tours with annotations
- Integrate AR measurements
- Enable social sharing of AR experiences

## Support

Need help? Check these resources:
- **Google Model Viewer**: https://modelviewer.dev/
- **ARCore**: https://developers.google.com/ar
- **ARKit**: https://developer.apple.com/augmented-reality/
- **glTF Format**: https://www.khronos.org/gltf/

## Quick Checklist

- [ ] Run migration
- [ ] Upload 3D model to a property
- [ ] Enable AR tour in admin panel
- [ ] Set AR model scale
- [ ] Choose placement guide
- [ ] Test on desktop
- [ ] Test on iOS device
- [ ] Test on Android device
- [ ] Check performance
- [ ] Launch to users! 🚀

---

**Ready to go!** Your AR Property Tours are now set up and ready to provide immersive experiences to your users.
