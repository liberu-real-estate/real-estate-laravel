# 3D Property Models Feature

## Overview

The 3D Property Models feature provides an immersive viewing experience by allowing users to interact with three-dimensional models of properties directly on property detail pages. Users can rotate, zoom, and pan around the model to get a complete understanding of the property's layout and features.

## Key Features

- **Interactive 3D Viewing**: Rotate, zoom, and pan property models using mouse or touch controls
- **WebGL-Powered**: Utilizes Three.js for high-performance 3D rendering
- **Cross-Browser Compatible**: Works on all modern browsers with WebGL support
- **Responsive Design**: Adapts to all screen sizes from mobile to desktop
- **Fallback Support**: Graceful degradation with helpful messages for unsupported browsers
- **Admin Integration**: Easy management through Filament admin panel

## User Interface

### Controls

**Mouse Controls:**
- **Rotate**: Left-click and drag
- **Zoom**: Scroll wheel up/down
- **Pan**: Right-click and drag

**Touch Controls (Mobile/Tablet):**
- **Rotate**: One finger drag
- **Zoom**: Pinch gesture
- **Pan**: Two finger drag

**Additional:**
- **Reset View**: Button to return to default camera position

### Visual Elements

- Clear header with 3D icon
- Loading indicator during model load
- Control instructions footer
- Error messages when needed
- Dark mode support

## Technical Details

### Technologies Used

- **Three.js**: Core 3D rendering library
- **GLTFLoader**: For loading 3D model files
- **OrbitControls**: For camera manipulation
- **Laravel Blade**: For component templating
- **Livewire**: For integration with property detail pages

### Supported 3D Formats

- **GLB** (glTF Binary) - Recommended
- **GLTF** (glTF JSON)

### Browser Requirements

- WebGL support (Chrome 56+, Firefox 51+, Safari 11+, Edge 79+)
- JavaScript enabled
- Minimum 2GB RAM recommended for complex models

## Database Schema

```sql
ALTER TABLE properties ADD COLUMN model_3d_url VARCHAR(255) NULL;
```

The `model_3d_url` field stores the URL to the 3D model file hosted on a CDN or cloud storage.

## Implementation Files

### Backend
- `database/migrations/2026_02_16_200000_add_model_3d_url_to_properties_table.php`
- `app/Models/Property.php` (updated)
- `app/Filament/Staff/Resources/Properties/PropertyResource.php` (updated)
- `database/seeders/PropertySeeder.php` (updated with sample data)

### Frontend
- `resources/js/model-3d-viewer.js` - Main 3D viewer component
- `resources/js/app.js` (updated)
- `resources/views/components/model-3d-viewer.blade.php` - Blade component
- `resources/views/livewire/property-detail.blade.php` (updated)
- `package.json` (Three.js dependency added)

### Testing
- `tests/Feature/Property3DModelTest.php` - Comprehensive test suite

## Usage

### For Property Managers

1. **Access Admin Panel**
   - Log into Filament admin panel
   - Navigate to Properties section

2. **Add/Edit Property**
   - Select a property or create new one
   - Find the "3D Model URL" field
   - Enter the complete URL to your 3D model file
   - Example: `https://cdn.example.com/models/property-123.glb`

3. **Save and Verify**
   - Save the property
   - Visit the property detail page on the frontend
   - The 3D viewer will appear automatically

### For Developers

**Adding Sample Data:**
```php
php artisan db:seed --class=PropertySeeder
```

**Testing a Property:**
```php
php artisan tinker
$property = Property::find(1);
$property->model_3d_url = 'https://threejs.org/examples/models/gltf/LittlestTokyo.glb';
$property->save();
```

**Creating Custom 3D Models:**
1. Use Blender, SketchUp, or similar 3D software
2. Export as GLB or GLTF format
3. Optimize for web (reduce polygons, compress textures)
4. Upload to CDN or cloud storage
5. Add URL to property in admin panel

## API

### Blade Component

```blade
<x-model-3d-viewer 
    :modelUrl="$property->model_3d_url" 
    :propertyTitle="$property->title" 
/>
```

**Parameters:**
- `modelUrl` (required): URL to the 3D model file
- `propertyTitle` (optional): Property name for accessibility

### JavaScript API

```javascript
// Initialize viewer
const viewer = new Model3DViewer('container-id', 'model-url.glb');

// Reset camera
viewer.resetCamera();

// Clean up
viewer.dispose();
```

## Performance Considerations

### Model Optimization

- **File Size**: Keep under 10MB for best performance
- **Polygon Count**: Target < 50,000 triangles
- **Texture Size**: Use 1024x1024 or smaller
- **Compression**: Enable glTF compression when exporting

### Loading Strategy

- Models load on-demand when viewer is rendered
- Loading indicator shown during download
- Automatic scaling and centering of models
- Efficient memory management with cleanup

## Security

- ✅ URL validation in admin panel
- ✅ XSS protection through Blade templating
- ✅ No direct file uploads (URL-only approach)
- ✅ CodeQL security scan passed
- ✅ CORS handling for external model URLs

## Troubleshooting

### Model Not Displaying

**Check:**
1. Browser supports WebGL (visit https://get.webgl.org/)
2. Model URL is publicly accessible
3. File is in GLB or GLTF format
4. CORS is enabled on hosting server

**Common Issues:**
- **Large Files**: Models over 50MB may timeout
- **Invalid Format**: Only GLB/GLTF supported
- **Network Issues**: Check internet connection
- **CORS Errors**: Enable CORS on hosting server

### Performance Issues

**Solutions:**
1. Reduce model complexity in 3D software
2. Compress textures
3. Use GLB instead of GLTF (better compression)
4. Host on CDN for faster loading
5. Close other browser tabs

## Future Enhancements

Potential improvements for future versions:

- [ ] Multiple 3D models per property (different rooms/views)
- [ ] Annotations and hotspots on models
- [ ] Measurement tools
- [ ] Virtual Reality (VR) support
- [ ] Augmented Reality (AR) support
- [ ] Walk-through animations
- [ ] Day/night lighting toggles
- [ ] Direct 3D model upload in admin panel
- [ ] Model thumbnail generation
- [ ] Progressive loading for large models

## Testing

### Unit Tests

Run the test suite:
```bash
php artisan test --filter=Property3DModelTest
```

### Manual Testing Checklist

- [ ] Model loads correctly
- [ ] Controls work (rotate, zoom, pan)
- [ ] Reset view button functions
- [ ] Fallback message shows without model URL
- [ ] Error handling works for invalid URLs
- [ ] Responsive on mobile devices
- [ ] Dark mode styling correct
- [ ] Performance acceptable with complex models

## Resources

### Sample 3D Models
- Three.js Examples: https://threejs.org/examples/
- Khronos glTF Samples: https://github.com/KhronosGroup/glTF-Sample-Models
- Sketchfab: https://sketchfab.com/ (many free models)

### Tools
- Blender (Free): https://www.blender.org/
- glTF Viewer: https://gltf-viewer.donmccurdy.com/
- Three.js Editor: https://threejs.org/editor/

### Documentation
- Three.js Docs: https://threejs.org/docs/
- glTF Format: https://www.khronos.org/gltf/
- WebGL Guide: https://webglfundamentals.org/

## Support

For issues or questions:
1. Check the Quick Start Guide: `3D_PROPERTY_MODELS_QUICKSTART.md`
2. Review Implementation Summary: `3D_PROPERTY_MODELS_SUMMARY.md`
3. Check browser console for errors
4. Verify model URL accessibility
5. Contact development team with:
   - Browser and version
   - Device type
   - Model URL
   - Error messages
   - Screenshots

## Version History

**v1.0** (February 16, 2026)
- Initial release
- Basic 3D viewer with OrbitControls
- Admin panel integration
- Comprehensive test suite
- Complete documentation

---

**Feature Status**: ✅ Production Ready
**Maintainer**: Development Team
**Last Updated**: February 16, 2026
