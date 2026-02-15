# Virtual Staging Tool

## Overview

The Virtual Staging Tool allows users to upload property images and apply virtual staging to showcase properties with different interior design styles. This feature helps real estate agents present empty or poorly furnished properties in a more appealing way.

## Features

- **Image Upload**: Upload property photos to be staged
- **Multiple Staging Styles**: Choose from 8 different interior design styles:
  - Modern
  - Traditional
  - Minimalist
  - Luxury
  - Industrial
  - Scandinavian
  - Contemporary
  - Rustic
- **Auto-Staging**: Automatically stage images upon upload
- **Original Preservation**: Original images are always preserved
- **Multiple Versions**: Create multiple staged versions of the same image
- **API Access**: Full REST API for programmatic access
- **Admin UI**: Filament-based admin interface for managing staged images
- **Livewire Components**: Interactive frontend components for property galleries

## Architecture

### Database Schema

The virtual staging feature extends the `images` table with the following fields:

- `is_staged` (boolean): Indicates if this is a staged image
- `original_image_id` (foreign key): Links to the original image
- `staging_style` (string): The applied staging style
- `staging_metadata` (JSON): Additional staging information
- `staging_provider` (string): The provider used for staging (mock, openai, etc.)
- `file_path`, `file_name`, `mime_type`: File storage information

### Models

#### Image Model (`App\Models\Image`)

Enhanced with virtual staging capabilities:

```php
// Check if an image is staged
$image->isStaged(); // Returns boolean

// Get staged versions of an original image
$image->stagedVersions; // Collection of Image models

// Get the original image from a staged version
$image->originalImage; // Image model

// Check if image has staged versions
$image->hasStagedVersions(); // Returns boolean

// Get image URL
$image->url; // Returns asset URL
```

### Services

#### VirtualStagingService (`App\Services\VirtualStagingService`)

Core service for virtual staging operations:

```php
use App\Services\VirtualStagingService;

$service = app(VirtualStagingService::class);

// Upload an image
$image = $service->uploadImage($property, $uploadedFile, $style, $autoStage);

// Stage an existing image
$stagedImage = $service->stageImage($originalImage, 'modern');

// Get available staging styles
$styles = $service->getStagingStyles();

// Get property images
$images = $service->getPropertyImages($property, $includeStaged = true);

// Delete an image (and its staged versions)
$service->deleteImage($image);
```

## API Endpoints

All API endpoints require authentication via Laravel Sanctum.

### Get Staging Styles
```http
GET /api/staging/styles
```

**Response:**
```json
{
  "success": true,
  "data": {
    "styles": {
      "modern": "Modern",
      "traditional": "Traditional",
      ...
    }
  }
}
```

### Upload Image
```http
POST /api/properties/{property}/images/upload
Content-Type: multipart/form-data

Parameters:
- image (required): Image file (JPEG, PNG)
- staging_style (optional): Style to apply if auto-staging
- auto_stage (optional): Boolean to automatically stage
```

**Response:**
```json
{
  "success": true,
  "message": "Image uploaded successfully",
  "data": {
    "image": {
      "id": 1,
      "property_id": 5,
      "file_name": "property.jpg",
      "url": "http://example.com/storage/property-images/...",
      "is_staged": false,
      "has_staged_versions": true,
      "staged_versions": [...]
    }
  }
}
```

### Stage Existing Image
```http
POST /api/images/{image}/stage
Content-Type: application/json

{
  "staging_style": "modern",
  "options": {}
}
```

**Response:**
```json
{
  "success": true,
  "message": "Image staged successfully",
  "data": {
    "staged_image": {
      "id": 2,
      "url": "...",
      "is_staged": true,
      "staging_style": "modern",
      "staging_metadata": {...}
    }
  }
}
```

### Get Property Images
```http
GET /api/properties/{property}/images
```

**Response:**
```json
{
  "success": true,
  "data": {
    "images": [...]
  }
}
```

### Delete Image
```http
DELETE /api/images/{image}
```

**Response:**
```json
{
  "success": true,
  "message": "Image deleted successfully"
}
```

## Livewire Components

### VirtualStagingGallery

Display and manage property images with virtual staging capabilities.

```blade
<livewire:virtual-staging-gallery :property="$property" />
```

**Features:**
- Upload new images
- View original and staged versions
- Apply staging to existing images
- Delete images
- Responsive grid layout

## Configuration

Configuration is stored in `config/virtual-staging.php`:

```php
return [
    // Staging provider: 'mock', 'openai', 'stable-diffusion'
    'provider' => env('VIRTUAL_STAGING_PROVIDER', 'mock'),
    
    // API keys for external providers
    'api' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
        ],
    ],
    
    // Image settings
    'images' => [
        'max_size' => 10240, // KB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/jpg'],
    ],
];
```

### Environment Variables

Add to your `.env` file:

```env
VIRTUAL_STAGING_PROVIDER=mock
VIRTUAL_STAGING_MAX_SIZE=10240
VIRTUAL_STAGING_CACHE_ENABLED=true
VIRTUAL_STAGING_CACHE_TTL=3600

# For OpenAI integration (future)
# OPENAI_API_KEY=your-api-key-here
# OPENAI_STAGING_MODEL=dall-e-3

# For Stable Diffusion integration (future)
# STABLE_DIFFUSION_API_KEY=your-api-key-here
# STABLE_DIFFUSION_ENDPOINT=https://api.example.com
```

## Installation

1. Run migrations:
```bash
php artisan migrate
```

2. Ensure storage is linked:
```bash
php artisan storage:link
```

3. Set appropriate permissions:
```bash
chmod -R 775 storage/app/public
```

## Usage Examples

### Basic Upload and Stage

```php
use App\Models\Property;
use App\Services\VirtualStagingService;
use Illuminate\Http\UploadedFile;

$property = Property::find(1);
$file = request()->file('image');
$service = app(VirtualStagingService::class);

// Upload and auto-stage
$image = $service->uploadImage($property, $file, 'modern', true);

// Upload without staging
$image = $service->uploadImage($property, $file);

// Stage later
$stagedImage = $service->stageImage($image, 'luxury');
```

### Frontend Usage

```blade
<!-- In your property detail view -->
@livewire('virtual-staging-gallery', ['property' => $property])
```

### API Usage (JavaScript)

```javascript
// Upload image
const formData = new FormData();
formData.append('image', imageFile);
formData.append('staging_style', 'modern');
formData.append('auto_stage', true);

const response = await fetch(`/api/properties/${propertyId}/images/upload`, {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
  },
  body: formData
});

const result = await response.json();
console.log(result.data.image);
```

## Testing

Run tests with:

```bash
# Unit tests
php artisan test --filter VirtualStagingServiceTest

# Feature tests
php artisan test --filter VirtualStagingApiTest

# All virtual staging tests
php artisan test --filter VirtualStaging
```

## Future Enhancements

1. **AI Integration**: Integrate with real AI staging services (OpenAI DALL-E, Stable Diffusion)
2. **Batch Processing**: Upload and stage multiple images at once
3. **Custom Styles**: Allow users to define custom staging styles
4. **Preview Before Staging**: Show preview of staged result before saving
5. **Comparison View**: Side-by-side comparison of original vs staged
6. **Download Options**: Export staged images in various formats/resolutions
7. **Watermarking**: Add optional watermarks to staged images
8. **Analytics**: Track which staging styles perform best

## Troubleshooting

### Images not displaying
- Check that `storage:link` has been run
- Verify file permissions on `storage/app/public`
- Check that file paths in database match actual files

### Upload fails
- Check `VIRTUAL_STAGING_MAX_SIZE` in config
- Verify allowed file types in configuration
- Ensure storage disk has sufficient space

### Staging fails
- Verify staging provider is configured correctly
- Check API keys if using external providers
- Review error logs in `storage/logs`

## Security Considerations

1. **File Validation**: All uploads are validated for type and size
2. **Authentication**: All API endpoints require authentication
3. **Authorization**: Users can only access images for properties they have permission to
4. **File Storage**: Images are stored in isolated storage with proper permissions
5. **Input Sanitization**: All inputs are validated and sanitized

## License

This feature is part of the Liberu Real Estate application and follows the same MIT license.
