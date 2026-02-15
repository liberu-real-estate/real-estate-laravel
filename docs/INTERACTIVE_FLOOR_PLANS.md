# Interactive Floor Plans Feature

## Overview
The Interactive Floor Plans feature allows property administrators to upload floor plan images and add interactive annotations (room markers and points of interest) directly within the admin interface. These interactive floor plans are then displayed on the property detail pages for potential buyers/renters.

## Features

### Admin Interface (Filament)
- **Floor Plan Upload**: Upload floor plan images (PNG, JPG, SVG)
- **Interactive Editor**: Click on the uploaded image to add annotations
- **Two Annotation Types**:
  - **Room Markers** (blue circles): Mark rooms like "Living Room", "Kitchen", "Bedroom"
  - **Point of Interest Markers** (red circles): Mark features like "Balcony", "Main Entrance", "Storage"
- **Annotation Management**: Edit labels and remove annotations as needed
- **Clear All**: Remove all annotations at once

### Frontend Display
- **Interactive Viewer**: Displays the floor plan with all annotations
- **Click to View Details**: Click on any annotation to see its label
- **Hover Effects**: Visual feedback when hovering over annotations
- **Responsive Design**: Works on all screen sizes
- **Legend**: Shows what different marker colors represent

## Usage

### For Administrators

1. Navigate to the Property edit page in Filament admin
2. Scroll to the "Interactive Floor Plan" section
3. Click "Choose file" to upload a floor plan image
4. Once uploaded, the image will appear in an editable canvas
5. Select "Add Room" or "Add Marker" button
6. Click on the floor plan where you want to place the annotation
7. Edit the label text for the annotation
8. Save the property

### Database Schema

The feature uses two fields in the `properties` table:
- `floor_plan_data` (JSON): Stores the image data and annotations
- `floor_plan_image` (VARCHAR): Optional separate image path

### Data Structure

```json
{
  "image": "data:image/png;base64,...",
  "annotations": [
    {
      "type": "room",
      "x": 100,
      "y": 150,
      "label": "Living Room"
    },
    {
      "type": "marker",
      "x": 200,
      "y": 250,
      "label": "Balcony"
    }
  ]
}
```

## Technical Implementation

### Components Created

1. **FloorPlanEditor.php**: Custom Filament form component
2. **floor-plan-editor.blade.php**: Editor view with Alpine.js
3. **floor-plan-viewer.blade.php**: Frontend viewer component

### Dependencies
- Alpine.js (already included in the project)
- HTML5 Canvas API (native browser support)

### Files Modified

1. `app/Models/Property.php`: Added floor_plan_data and floor_plan_image to fillable array and casts
2. `app/Filament/Staff/Resources/Properties/PropertyResource.php`: Added FloorPlanEditor to form
3. `resources/views/livewire/property-detail.blade.php`: Added floor plan viewer

## Future Enhancements

Potential improvements for future versions:
- Support for multiple floor levels
- Measurement tools
- 3D floor plan integration
- Export floor plans as PDF
- Room dimension annotations
- Virtual tour integration
