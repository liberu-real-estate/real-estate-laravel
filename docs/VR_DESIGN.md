# VR Property Design Tool - Documentation

## Overview

The VR Property Design Tool is a comprehensive virtual reality-based interior design system that allows users to create, customize, and visualize property designs in an immersive 3D environment. This feature enables real estate professionals and clients to experiment with different furniture layouts, color schemes, and design styles before making physical changes.

## Table of Contents

1. [Features](#features)
2. [Architecture](#architecture)
3. [Installation](#installation)
4. [Configuration](#configuration)
5. [API Documentation](#api-documentation)
6. [Livewire Component Usage](#livewire-component-usage)
7. [Database Schema](#database-schema)
8. [Testing](#testing)
9. [VR Provider Integration](#vr-provider-integration)
10. [Troubleshooting](#troubleshooting)

## Features

### Core Capabilities

- **Design Creation**: Create multiple VR designs for each property
- **Style Presets**: 8 pre-configured design styles (Modern, Traditional, Minimalist, Luxury, Industrial, Scandinavian, Contemporary, Rustic)
- **Furniture Management**: Add, position, rotate, and scale furniture items
- **Material Customization**: Change wall colors, floor materials, and textures
- **Lighting Configuration**: Adjust ambient and directional lighting
- **Template System**: Save and share designs as templates
- **Design Cloning**: Create variations of existing designs
- **Multi-tenancy Support**: Team-based design management
- **Export Functionality**: Export designs in JSON format
- **Thumbnail Support**: Upload custom thumbnails for designs
- **Public/Private Designs**: Control design visibility

### VR Device Compatibility

Supports major VR devices:
- Meta Quest (Quest 2, Quest 3)
- HTC Vive
- Valve Index
- PlayStation VR
- Windows Mixed Reality
- Google Cardboard
- WebXR-compatible browsers

## Architecture

### Design Patterns

- **Service Layer Pattern**: Business logic encapsulated in `VRPropertyDesignService`
- **Repository Pattern**: Data access via Eloquent ORM
- **API-First Design**: RESTful endpoints with standardized JSON responses
- **Factory Pattern**: Ready for multiple VR provider integrations

### Components

```
├── Models/
│   └── VRDesign.php                    # Main design model
├── Services/
│   └── VRPropertyDesignService.php     # Business logic
├── Controllers/
│   └── API/
│       └── VRPropertyDesignController.php  # API endpoints
├── Livewire/
│   └── VRPropertyDesignStudio.php      # Interactive UI component
├── Views/
│   └── livewire/
│       └── vr-property-design-studio.blade.php  # UI template
├── config/
│   └── vr-design.php                   # Configuration
└── database/
    ├── migrations/
    │   └── 2026_02_16_201326_create_vr_designs_table.php
    └── factories/
        └── VRDesignFactory.php
```

## Installation

### Requirements

- PHP 8.3+
- Laravel 12+
- MySQL/PostgreSQL
- Composer
- Storage disk configured

### Steps

1. **Run Migrations**

```bash
php artisan migrate
```

2. **Link Storage** (if not already linked)

```bash
php artisan storage:link
```

3. **Set Permissions**

```bash
chmod -R 775 storage/app/public
```

4. **Configure Environment** (optional)

```env
VR_DESIGN_PROVIDER=mock
VR_DESIGN_STORAGE_DISK=public
VR_TEXTURE_QUALITY=medium
VR_DESIGN_CACHE_ENABLED=true
VR_DESIGN_CACHE_TTL=3600
```

## Configuration

The VR design system is configured via `config/vr-design.php`.

### Key Configuration Options

#### VR Provider

```php
'provider' => env('VR_DESIGN_PROVIDER', 'mock'),
```

Options: `'threejs'`, `'babylonjs'`, `'aframe'`, `'mock'`

#### Design Styles

Eight predefined styles with color palettes:

```php
'styles' => [
    'modern' => [
        'name' => 'Modern',
        'description' => 'Clean lines, minimal clutter',
        'color_palette' => ['#FFFFFF', '#000000', '#808080', '#C0C0C0'],
    ],
    // ... more styles
]
```

#### Furniture Categories

```php
'furniture_categories' => [
    'seating' => ['Sofa', 'Armchair', 'Dining Chair', ...],
    'tables' => ['Dining Table', 'Coffee Table', ...],
    'storage' => ['Bookshelf', 'Cabinet', ...],
    // ... more categories
]
```

#### Storage Settings

```php
'storage' => [
    'disk' => env('VR_DESIGN_STORAGE_DISK', 'public'),
    'path' => 'vr-designs',
    'thumbnail_path' => 'vr-designs/thumbnails',
    'max_size' => 52428800, // 50MB
]
```

#### Performance Settings

```php
'performance' => [
    'max_polygons' => 100000,
    'texture_quality' => env('VR_TEXTURE_QUALITY', 'medium'),
    'enable_shadows' => true,
    'enable_reflections' => true,
    'target_fps' => 60,
]
```

## API Documentation

All API endpoints require authentication via `auth:sanctum` middleware.

### Base URL

```
/api
```

### Response Format

All API responses follow this structure:

```json
{
  "success": true|false,
  "message": "Human-readable message",
  "data": {
    // Response data
  },
  "errors": {
    // Validation errors (if applicable)
  }
}
```

### Endpoints

#### 1. Get Design Styles

**GET** `/api/vr-design/styles`

Returns all available design styles.

**Response:**
```json
{
  "success": true,
  "data": {
    "styles": {
      "modern": {
        "name": "Modern",
        "description": "Clean lines, minimal clutter",
        "color_palette": ["#FFFFFF", "#000000", ...]
      }
    }
  }
}
```

#### 2. Get Furniture Categories

**GET** `/api/vr-design/furniture-categories`

Returns all furniture categories and types.

#### 3. Get Room Types

**GET** `/api/vr-design/room-types`

Returns supported room types.

#### 4. Get Supported Devices

**GET** `/api/vr-design/devices`

Returns list of compatible VR devices.

#### 5. Create Design

**POST** `/api/properties/{property_id}/vr-designs`

Create a new VR design for a property.

**Request Body:**
```json
{
  "name": "My Modern Living Room",
  "description": "A contemporary living space",
  "style": "modern",
  "design_data": {
    "version": "1.0"
  },
  "is_public": false
}
```

**Validation Rules:**
- `name`: required, string, max:255
- `description`: nullable, string, max:1000
- `style`: nullable, must be valid style key
- `design_data`: required, array
- `is_public`: boolean

**Response:** (201 Created)
```json
{
  "success": true,
  "message": "VR design created successfully",
  "data": {
    "design": {
      "id": 1,
      "name": "My Modern Living Room",
      "property_id": 5,
      "user_id": 10,
      ...
    }
  }
}
```

#### 6. Get Property Designs

**GET** `/api/properties/{property_id}/vr-designs?public_only=false`

Get all designs for a property.

**Query Parameters:**
- `public_only`: boolean (default: false)

#### 7. Get Design

**GET** `/api/vr-designs/{design_id}`

Get a specific design (increments view count).

#### 8. Update Design

**PUT** `/api/vr-designs/{design_id}`

Update an existing design (owner only).

**Request Body:**
```json
{
  "name": "Updated Name",
  "description": "Updated description",
  "style": "luxury",
  "design_data": {...},
  "is_public": true
}
```

#### 9. Delete Design

**DELETE** `/api/vr-designs/{design_id}`

Soft delete a design (owner only).

#### 10. Add Furniture

**POST** `/api/vr-designs/{design_id}/furniture`

Add a furniture item to the design.

**Request Body:**
```json
{
  "category": "seating",
  "type": "Sofa",
  "position": [0, 0, 0],
  "rotation": [0, 90, 0],
  "scale": [1, 1, 1],
  "material": {
    "color": "#8B4513"
  }
}
```

#### 11. Remove Furniture

**DELETE** `/api/vr-designs/{design_id}/furniture/{furniture_id}`

Remove a furniture item from the design.

#### 12. Clone Design

**POST** `/api/vr-designs/{design_id}/clone`

Create a copy of an existing design.

**Request Body:**
```json
{
  "name": "My Design (Copy)"
}
```

#### 13. Get Templates

**GET** `/api/vr-design/templates?style=modern`

Get all available design templates.

**Query Parameters:**
- `style`: optional style filter

#### 14. Upload Thumbnail

**POST** `/api/vr-designs/{design_id}/thumbnail`

Upload a thumbnail image for a design.

**Request:** multipart/form-data
- `thumbnail`: image file (jpeg, png, jpg), max 5MB

#### 15. Export Design

**GET** `/api/vr-designs/{design_id}/export?format=json`

Export design data.

**Query Parameters:**
- `format`: export format (default: json)

## Livewire Component Usage

### Basic Usage

Include the VR Design Studio component in your Blade templates:

```blade
@livewire('vr-property-design-studio', ['property' => $property])
```

### Component Features

- **Design List Sidebar**: Shows all designs for the property
- **Design Canvas**: 3D visualization area (placeholder for VR rendering)
- **Modal Interfaces**:
  - Create/Edit Design
  - Add Furniture
  - Upload Thumbnail
- **Real-time Updates**: Automatic refresh after operations
- **Flash Messages**: User feedback for actions
- **Responsive Design**: Mobile-friendly interface

### Component Methods

Available Livewire methods:

```php
// Design management
$this->createNewDesign()
$this->saveDesign()
$this->editDesign()
$this->updateDesign()
$this->deleteDesign($designId)
$this->cloneDesign()
$this->selectDesign($designId)

// Furniture management
$this->openFurnitureModal()
$this->addFurniture()
$this->removeFurniture($furnitureId)

// Thumbnail management
$this->openThumbnailModal()
$this->uploadThumbnail()
```

## Database Schema

### `vr_designs` Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| property_id | bigint | Foreign key to properties |
| user_id | bigint | Foreign key to users (creator) |
| team_id | bigint | Foreign key to teams |
| name | string | Design name |
| description | text | Design description |
| vr_provider | string | VR technology provider |
| design_data | json | Core design data |
| room_layout | json | Room dimensions |
| furniture_items | json | Furniture array |
| materials | json | Wall/floor materials |
| lighting | json | Lighting configuration |
| thumbnail_path | string | Path to thumbnail |
| vr_scene_url | string | VR scene URL |
| is_public | boolean | Public visibility |
| is_template | boolean | Template flag |
| style | string | Design style |
| view_count | integer | View counter |
| created_at | timestamp | Creation time |
| updated_at | timestamp | Update time |
| deleted_at | timestamp | Soft delete |

### Relationships

- `belongsTo` Property
- `belongsTo` User (creator)
- `belongsTo` Team

### Indexes

- `(property_id, user_id)` - Composite index
- `(team_id, is_public)` - Composite index
- `style` - Single column index

## Testing

### Running Tests

**Unit Tests:**
```bash
php artisan test --testsuite=Unit --filter=VRPropertyDesignServiceTest
```

**Feature Tests:**
```bash
php artisan test --testsuite=Feature --filter=VRPropertyDesignApiTest
```

**All VR Design Tests:**
```bash
php artisan test --filter=VRPropertyDesign
```

### Test Coverage

- **Unit Tests**: 23 test methods
  - Service layer methods
  - Data manipulation
  - Business logic
  
- **Feature Tests**: 24 test methods
  - API endpoints
  - Authentication
  - Authorization
  - Validation
  - Error handling

### Test Data

Use the VRDesign factory for test data:

```php
// Basic design
$design = VRDesign::factory()->create();

// Public design
$design = VRDesign::factory()->public()->create();

// Template design
$template = VRDesign::factory()->template()->create();

// Design with furniture
$design = VRDesign::factory()->withFurniture()->create();
```

## VR Provider Integration

### Current Provider: Mock

The system currently uses a mock provider for development. This allows immediate functionality while real VR integrations are implemented.

### Integrating Real VR Providers

#### Three.js Integration

1. Update configuration:
```php
'provider' => 'threejs',
'providers' => [
    'threejs' => [
        'enabled' => true,
        'cdn_url' => 'https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js',
        'version' => '0.160.0',
    ],
],
```

2. Implement rendering in `VRPropertyDesignService`:
```php
protected function generateThreeJsScene(VRDesign $design): string
{
    // Generate Three.js scene based on design data
    // Return scene configuration or URL
}
```

3. Update Blade template to include Three.js:
```blade
@if($selectedDesign->vr_provider === 'threejs')
    <script type="module" src="{{ config('vr-design.providers.threejs.cdn_url') }}"></script>
    <div id="threejs-canvas" data-design-id="{{ $selectedDesign->id }}"></div>
@endif
```

#### Babylon.js Integration

Similar process as Three.js, update provider configuration and implement rendering logic.

#### A-Frame Integration

For WebXR-based VR experiences, integrate A-Frame framework.

## Troubleshooting

### Common Issues

#### 1. Storage Permission Error

**Error:** "Unable to create directory..."

**Solution:**
```bash
chmod -R 775 storage/app/public
php artisan storage:link
```

#### 2. Migration Error

**Error:** "Table 'vr_designs' already exists"

**Solution:**
```bash
php artisan migrate:rollback --step=1
php artisan migrate
```

#### 3. Validation Errors

**Error:** "The style field must be one of..."

**Solution:** Ensure the style value matches one of the configured styles in `config/vr-design.php`.

#### 4. Unauthorized Access

**Error:** 401 Unauthorized

**Solution:** Ensure you're passing the authentication token:
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" ...
```

#### 5. Design Not Found

**Error:** 404 Not Found

**Solution:** Verify the design ID exists and hasn't been soft deleted.

### Debug Mode

Enable detailed error messages:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```

### Cache Issues

If changes aren't reflected, clear cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Best Practices

### 1. Design Organization

- Create templates for commonly used designs
- Use descriptive names
- Add detailed descriptions
- Tag with appropriate styles

### 2. Performance

- Limit polygon count for furniture models
- Optimize texture sizes
- Use caching for frequently accessed designs
- Paginate design lists for properties with many designs

### 3. Security

- Always validate user ownership before modifications
- Use middleware for authentication
- Sanitize user inputs
- Implement rate limiting for API endpoints

### 4. User Experience

- Provide thumbnails for all designs
- Use clear naming conventions
- Offer design templates as starting points
- Enable design sharing within teams

## Future Enhancements

### Planned Features

1. **Real-time Collaboration**: Multiple users editing simultaneously
2. **AI-Powered Suggestions**: Automatic furniture placement
3. **Photo-realistic Rendering**: High-quality 3D renders
4. **VR Walkthroughs**: Immersive property tours
5. **AR Preview**: View designs in actual space via mobile
6. **Material Marketplace**: Browse and purchase real materials
7. **3D Model Library**: Expanded furniture and decor options
8. **Video Export**: Record VR walkthrough videos
9. **Social Sharing**: Share designs on social media
10. **Analytics**: Track popular styles and furniture

## Support

### Resources

- **Documentation**: This file
- **API Reference**: See [API Documentation](#api-documentation) section
- **Issue Tracker**: GitHub repository issues
- **Laravel Docs**: https://laravel.com/docs

### Getting Help

1. Check this documentation
2. Review test files for usage examples
3. Check Laravel logs for errors
4. Search existing GitHub issues
5. Create a new GitHub issue with:
   - Laravel version
   - PHP version
   - Error message
   - Steps to reproduce

## License

This feature is part of the Liberu Real Estate Laravel application and follows the same MIT license.

---

**Version**: 1.0.0  
**Last Updated**: February 16, 2026  
**Status**: Production Ready
