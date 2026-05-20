# 3D Property Viewer Documentation

## Overview
The 3D Property Viewer feature allows users to upload and view interactive 3D models of properties directly on the property detail pages. This enhancement provides a more immersive experience for potential buyers and renters.

## Features
- ✅ Upload 3D model files (GLB, GLTF formats)
- ✅ Interactive 3D viewer with camera controls
- ✅ Auto-rotation for better showcase
- ✅ Touch gestures support (drag to rotate, pinch to zoom)
- ✅ Augmented Reality (AR) support on compatible devices
- ✅ Single file upload per property (latest upload replaces previous)
- ✅ Maximum file size: 50MB

## Supported File Formats
- `.glb` (Binary GLTF) - Recommended
- `.gltf` (GLTF JSON)

**Note**: GLB format is preferred as it includes all textures and materials in a single binary file.

## How to Use

### For Administrators/Staff

1. **Navigate to Property Management**
   - Go to the Filament admin panel
   - Click on "Properties" in the navigation

2. **Upload a 3D Model**
   - Create a new property or edit an existing one
   - Scroll to the "3D Model (.glb or .gltf)" field
   - Click "Choose file" and select your 3D model
   - The system accepts GLB and GLTF formats up to 50MB
   - Click "Create" or "Save" to store the property with the 3D model

3. **View in Admin Panel**
   - In the properties list, a cube icon indicates properties with 3D models

### For End Users

1. **Viewing 3D Models**
   - Navigate to a property detail page
   - If a 3D model is available, it will appear below the main property image
   - Look for the "3D Property Model" section

2. **Interacting with the 3D Model**
   - **Desktop**: Click and drag to rotate the model
   - **Mobile**: Touch and drag to rotate, pinch to zoom
   - The model auto-rotates by default for easy viewing

3. **Augmented Reality (AR)**
   - On supported devices (iOS with ARKit, Android with ARCore)
   - Tap the AR icon in the viewer
   - Follow on-screen instructions to place the model in your real environment

## Technical Implementation

### Database Schema
A new column `model_3d_url` has been added to the `properties` table for future direct URL storage if needed.

### Media Collection
3D models are stored using Spatie Media Library in the `3d_models` collection with the following constraints:
- Single file per property
- Accepted MIME types: `model/gltf-binary`, `model/gltf+json`, `application/octet-stream`
- Maximum size: 50MB

### Frontend Integration
The viewer uses Google's `<model-viewer>` web component, which provides:
- Cross-browser compatibility
- Automatic lighting and shadows
- Built-in AR support
- Accessibility features

## Creating 3D Models

### Recommended Tools
- **Blender** (Free, open-source) - Best for creating and exporting GLB files
- **SketchUp** - Good for architectural models
- **Matterport** - For scanning real properties
- **3ds Max** - Professional 3D modeling
- **Cinema 4D** - High-end modeling and rendering

### Best Practices
1. **Optimize for Web**
   - Keep polygon count under 100k triangles
   - Use compressed textures (JPG for photos, PNG for transparency)
   - Limit texture size to 2048x2048 or smaller
   - Combine meshes where possible

2. **Export Settings (Blender)**
   - Format: glTF 2.0 (.glb)
   - Include: Selected Objects
   - Transform: +Y Up
   - Geometry: Apply Modifiers, UVs, Normals
   - Compression: Enable Draco mesh compression

3. **Testing**
   - Test your model at https://modelviewer.dev/ before uploading
   - Ensure textures load correctly
   - Check that the model is properly oriented

## Troubleshooting

### Model not displaying
- Verify the file format is GLB or GLTF
- Check file size is under 50MB
- Ensure the model has proper normals and UVs
- Test the model in an external viewer first

### Model looks incorrect
- Check texture paths in GLTF files (should be relative)
- Verify materials are using PBR (Physically Based Rendering)
- Ensure the model is properly scaled

### AR not working
- AR requires HTTPS
- Device must support ARKit (iOS) or ARCore (Android)
- Ensure camera permissions are granted

## Running Tests

To run the 3D model tests:

```bash
# Install dependencies
composer install
npm install

# Run PHP unit tests
php artisan test --filter Property3DModelTest

# Run all property tests
php artisan test tests/Unit/PropertyTest.php
php artisan test tests/Unit/Property3DModelTest.php
```

## Future Enhancements

Potential improvements for future versions:
- Multiple 3D models per property (interior/exterior)
- 3D model annotations/hotspots
- Measurement tools
- Virtual staging integration
- Floor plan to 3D conversion
- 3D model gallery with thumbnails
- Performance analytics for 3D views

## Browser Compatibility

The 3D viewer works on:
- ✅ Chrome 67+
- ✅ Firefox 63+
- ✅ Safari 12.1+
- ✅ Edge 79+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

AR support requires:
- iOS 12+ with ARKit
- Android 8+ with ARCore

## Security Considerations

- File uploads are validated for type and size
- Files are stored using Spatie Media Library with proper sanitization
- Only authenticated staff can upload 3D models
- Public access is read-only

## Support

For issues or questions about the 3D Property Viewer:
1. Check this documentation
2. Review test cases for expected behavior
3. Contact the development team

---

**Version**: 1.0.0  
**Last Updated**: February 2026
