# 3D Property Models - Quick Start Guide

## For Property Managers

### How to Add a 3D Model to a Property

1. **Obtain a 3D Model**
   - Create or commission a 3D model of your property in GLB or GLTF format
   - Upload it to a publicly accessible URL (CDN, cloud storage, etc.)

2. **Add to Property in Admin Panel**
   - Log into the admin panel
   - Navigate to Properties → Select your property
   - Find the "3D Model URL" field
   - Enter the complete URL to your 3D model file
   - Example: `https://cdn.example.com/models/property-123.glb`
   - Save the property

3. **Verify on Frontend**
   - Visit the property detail page
   - The "3D Property Model" section will appear automatically
   - Test the controls to ensure everything works

## For Developers

### Testing with Sample 3D Models

You can use these free sample 3D models for testing:

1. **Simple House Model** (Free from Three.js examples):
   ```
   https://threejs.org/examples/models/gltf/LittlestTokyo.glb
   ```

2. **Damaged Helmet** (Khronos sample):
   ```
   https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/master/2.0/DamagedHelmet/glTF-Binary/DamagedHelmet.glb
   ```

3. **Scene in a Bottle** (Microsoft sample):
   ```
   https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/master/2.0/SceneInABottle/glTF-Binary/SceneInABottle.glb
   ```

### Adding a Test Property with 3D Model

**Using Laravel Tinker:**
```php
php artisan tinker

$property = Property::find(1); // Replace with your property ID
$property->model_3d_url = 'https://threejs.org/examples/models/gltf/LittlestTokyo.glb';
$property->save();
```

**Using Filament Admin Panel:**
1. Go to Properties
2. Edit any property
3. Paste a sample URL in the "3D Model URL" field
4. Save

### Creating Your Own 3D Models

**Recommended Tools:**
- **Blender** (Free, Open Source) - https://www.blender.org/
- **SketchUp** (Free/Paid) - https://www.sketchup.com/
- **Autodesk Revit** (Professional) - For architects
- **Matterport** (3D Scanning) - For real properties

**Export Settings:**
- Format: GLB (preferred) or GLTF
- Optimize for web (reduce polygon count)
- Embed textures for GLB
- Keep file size under 10MB for best performance

**Hosting Options:**
- AWS S3 with CloudFront
- Google Cloud Storage
- Azure Blob Storage
- Cloudinary
- Your own CDN

### Example: Creating a Simple House in Blender

1. **Model the House**
   - Create basic geometry (walls, roof, windows, doors)
   - Keep polygon count reasonable (< 50k triangles)
   - Apply materials and textures

2. **Export as GLB**
   - File → Export → glTF 2.0
   - Format: glTF Binary (.glb)
   - Check "Apply Modifiers"
   - Check "Compression"
   - Export

3. **Upload to Hosting**
   - Upload the .glb file to your hosting service
   - Make sure CORS is enabled
   - Note the public URL

4. **Add to Property**
   - Use the URL in the admin panel
   - Test on the property detail page

## For End Users

### How to Use the 3D Viewer

**Mouse Controls:**
- **Rotate**: Left-click and drag
- **Zoom**: Scroll wheel up/down
- **Pan**: Right-click and drag (or Shift + left-click and drag)

**Touch Controls (Mobile/Tablet):**
- **Rotate**: One finger drag
- **Zoom**: Pinch gesture
- **Pan**: Two finger drag

**Keyboard:**
- Press "R" or click "Reset View" button to return to default view

**Tips:**
- Zoom in to see fine details
- Rotate to view from all angles
- Use the grid for spatial reference
- Click "Reset View" if you get lost

## Troubleshooting

### 3D Model Not Showing

1. **Check Browser Compatibility**
   - Visit https://get.webgl.org/ to verify WebGL support
   - Update your browser to the latest version
   - Try a different browser (Chrome, Firefox, Safari, Edge)

2. **Check Model URL**
   - Make sure the URL is publicly accessible
   - Test by opening the URL directly in your browser
   - Verify the file is in GLB or GLTF format
   - Check for CORS issues (browser console will show errors)

3. **Check File Size**
   - Large models (>50MB) may be slow to load
   - Consider optimizing the model
   - Check your internet connection

### Performance Issues

1. **Reduce Model Complexity**
   - Use tools like Blender to reduce polygon count
   - Compress textures
   - Remove unnecessary details

2. **Optimize Textures**
   - Use smaller texture sizes (1024x1024 or less)
   - Compress images (JPEG for photos, PNG for graphics)
   - Combine multiple textures where possible

3. **Check Device Performance**
   - Older devices may struggle with complex models
   - Close other browser tabs
   - Ensure device isn't overheating

### Error Messages

**"3D Model Not Available"**
- The property doesn't have a 3D model URL set
- Contact the property manager to add one

**"Failed to Load 3D Model"**
- The URL is invalid or inaccessible
- The file format is not supported
- There's a network issue

**"Your browser doesn't support WebGL"**
- Browser is too old
- WebGL is disabled in browser settings
- Graphics drivers need updating

## Best Practices

### For Property Managers
1. Always use high-quality 3D models
2. Include interior and exterior views
3. Keep file sizes reasonable (5-15MB)
4. Test on multiple devices before publishing
5. Update models when property changes

### For Developers
1. Always validate 3D model URLs
2. Test with various model complexities
3. Monitor performance metrics
4. Implement loading indicators
5. Handle errors gracefully
6. Enable CORS on hosting servers
7. Use CDN for better performance
8. Compress models before uploading

### For Users
1. Use latest browser versions
2. Ensure stable internet connection
3. Allow time for large models to load
4. Report issues to property managers
5. Try different devices if having issues

## Advanced Features (For Future Development)

Potential enhancements:
- Multiple views (interior, exterior, different rooms)
- Measurement tools
- Annotations on model (dimensions, features)
- Virtual reality (VR) support
- Augmented reality (AR) support
- Model comparison between properties
- Walk-through animations
- Day/night lighting toggles
- Furniture placement tool

## Resources

### Learning Resources
- Three.js Documentation: https://threejs.org/docs/
- Three.js Examples: https://threejs.org/examples/
- glTF Format: https://www.khronos.org/gltf/
- WebGL Fundamentals: https://webglfundamentals.org/

### Tools
- Blender: https://www.blender.org/
- glTF Viewer: https://gltf-viewer.donmccurdy.com/
- Three.js Editor: https://threejs.org/editor/

### Sample Models
- Khronos glTF Samples: https://github.com/KhronosGroup/glTF-Sample-Models
- Three.js Examples: https://github.com/mrdoob/three.js/tree/dev/examples/models
- Sketchfab: https://sketchfab.com/ (many free models available)

## Support

For technical support or questions:
1. Check this guide first
2. Review the implementation summary (3D_PROPERTY_MODELS_SUMMARY.md)
3. Check browser console for errors
4. Contact the development team with:
   - Browser and version
   - Device type
   - Model URL (if applicable)
   - Error messages
   - Screenshots

---

**Version**: 1.0
**Last Updated**: February 16, 2026
**Status**: Production Ready
