# Wishlist API Documentation

## Overview
The Wishlist API allows authenticated users to manage their favorite properties. Users can add properties to their wishlist, remove them, view all their favorited properties, and check if a specific property is in their wishlist.

## Authentication
All wishlist endpoints require authentication using Laravel Sanctum. Include the authentication token in the request header:
```
Authorization: Bearer {token}
```

## Endpoints

### 1. List User's Favorites
Retrieve all properties in the authenticated user's wishlist with pagination.

**Endpoint:** `GET /api/favorites`

**Authentication:** Required

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": 1,
      "title": "Luxury Villa in Beverly Hills",
      "description": "Beautiful 5-bedroom villa with ocean views",
      "location": "Beverly Hills, CA",
      "price": 2500000,
      "bedrooms": 5,
      "bathrooms": 4,
      "area_sqft": 4500,
      "property_type": "villa",
      "status": "available",
      "images": [...],
      "neighborhood": {...},
      "features": [...]
    }
  ],
  "current_page": 1,
  "per_page": 12,
  "total": 25
}
```

---

### 2. Add Property to Wishlist
Add a property to the authenticated user's wishlist.

**Endpoint:** `POST /api/favorites`

**Authentication:** Required

**Request Body:**
```json
{
  "property_id": 123
}
```

**Validation Rules:**
- `property_id`: required, must exist in properties table

**Response:** `201 Created`
```json
{
  "message": "Property added to wishlist successfully",
  "favorite": {
    "id": 1,
    "user_id": 5,
    "property_id": 123,
    "team_id": 2,
    "created_at": "2024-02-15T10:30:00.000000Z",
    "updated_at": "2024-02-15T10:30:00.000000Z",
    "property": {
      "id": 123,
      "title": "Modern Apartment Downtown",
      "price": 450000,
      ...
    }
  }
}
```

**Error Response:** `422 Unprocessable Entity`
```json
{
  "message": "Property is already in your wishlist"
}
```

**Error Response:** `422 Unprocessable Entity` (Invalid property_id)
```json
{
  "message": "Validation failed",
  "errors": {
    "property_id": ["The selected property id is invalid."]
  }
}
```

---

### 3. Remove Property from Wishlist
Remove a property from the authenticated user's wishlist.

**Endpoint:** `DELETE /api/favorites/{propertyId}`

**Authentication:** Required

**Path Parameters:**
- `propertyId`: The ID of the property to remove

**Response:** `200 OK`
```json
{
  "message": "Property removed from wishlist successfully"
}
```

**Error Response:** `404 Not Found`
```json
{
  "message": "Property not found in your wishlist"
}
```

---

### 4. Check if Property is Favorited
Check if a specific property is in the authenticated user's wishlist.

**Endpoint:** `GET /api/favorites/check/{propertyId}`

**Authentication:** Required

**Path Parameters:**
- `propertyId`: The ID of the property to check

**Response:** `200 OK`
```json
{
  "is_favorited": true
}
```

---

## Web Routes

### Wishlist Management Page
View and manage the user's wishlist through a web interface.

**Route:** `/wishlist`

**Authentication:** Required (auth, verified middleware)

**Features:**
- View all favorited properties in a grid layout
- Search through favorited properties
- Sort by date added or price
- Remove properties from wishlist
- Direct links to property details
- Responsive design for mobile/tablet/desktop

---

## Models and Relationships

### Favorite Model
```php
class Favorite extends Model
{
    protected $fillable = ['user_id', 'property_id', 'team_id'];
    
    // Relationships
    public function user(): BelongsTo
    public function property(): BelongsTo
    public function team(): BelongsTo
}
```

### User Model (Extended)
```php
// New relationships added
public function favorites(): HasMany
public function favoriteProperties(): BelongsToMany
```

### Property Model (Extended)
```php
// New relationships added
public function favorites(): HasMany
public function favoritedBy(): BelongsToMany
```

---

## Database Schema

### favorites Table
```sql
CREATE TABLE favorites (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    property_id BIGINT UNSIGNED NOT NULL,
    team_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE,
    UNIQUE KEY user_property_unique (user_id, property_id)
);
```

**Indexes:**
- Primary key on `id`
- Foreign keys on `user_id`, `property_id`, `team_id`
- Unique constraint on `(user_id, property_id)` to prevent duplicate favorites

---

## Error Handling

All endpoints follow consistent error handling:

### 401 Unauthorized
Returned when the user is not authenticated.
```json
{
  "message": "Unauthenticated."
}
```

### 422 Unprocessable Entity
Returned when validation fails.
```json
{
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### 404 Not Found
Returned when a resource is not found.
```json
{
  "message": "Resource not found"
}
```

### 400 Bad Request
Returned when an operation fails.
```json
{
  "message": "Failed to perform operation",
  "error": "Detailed error message"
}
```

---

## Usage Examples

### JavaScript/Fetch API
```javascript
// Add to wishlist
const addToWishlist = async (propertyId) => {
  const response = await fetch('/api/favorites', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify({ property_id: propertyId })
  });
  return await response.json();
};

// Remove from wishlist
const removeFromWishlist = async (propertyId) => {
  const response = await fetch(`/api/favorites/${propertyId}`, {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  return await response.json();
};

// Check if favorited
const isFavorited = async (propertyId) => {
  const response = await fetch(`/api/favorites/check/${propertyId}`, {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  const data = await response.json();
  return data.is_favorited;
};
```

### cURL Examples
```bash
# Add to wishlist
curl -X POST https://yourapp.com/api/favorites \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"property_id": 123}'

# Remove from wishlist
curl -X DELETE https://yourapp.com/api/favorites/123 \
  -H "Authorization: Bearer YOUR_TOKEN"

# Check if favorited
curl https://yourapp.com/api/favorites/check/123 \
  -H "Authorization: Bearer YOUR_TOKEN"

# List all favorites
curl https://yourapp.com/api/favorites \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Livewire Components

### WishlistManager Component
The main Livewire component for managing the wishlist page.

**Features:**
- Real-time search filtering
- Sortable columns (date added, price, title)
- Pagination (12 items per page)
- Instant property removal
- Event-driven updates

**Events:**
- `favoriteAdded` - Emitted when a property is added to wishlist
- `favoriteRemoved` - Emitted when a property is removed from wishlist

### PropertyDetail Component
Extended to include wishlist functionality.

**New Methods:**
- `toggleFavorite()` - Add/remove property from wishlist
- Property shows current favorite status

### PropertyList Component
Extended to include wishlist functionality.

**New Methods:**
- `toggleFavorite($propertyId)` - Add/remove property from wishlist
- `isFavorited($propertyId)` - Check if property is favorited
- Heart icon on each property card toggles favorite status

---

## Testing

### Unit Tests
- `FavoriteTest` - Tests model relationships and basic CRUD operations

### Feature Tests
- `FavoriteControllerTest` - Tests all API endpoints with various scenarios:
  - Authenticated access
  - Adding/removing favorites
  - Duplicate prevention
  - Team association
  - Validation

Run tests:
```bash
php artisan test --filter FavoriteTest
php artisan test --filter FavoriteControllerTest
```

---

## Notes

1. **Unique Constraint**: Users cannot add the same property to their wishlist twice. The database enforces this with a unique constraint on `(user_id, property_id)`.

2. **Team Support**: Favorites can be associated with teams for multi-tenant applications. The `team_id` is automatically set when a user belongs to a team.

3. **Soft Deletes**: Properties use soft deletes, so favorited properties that are deleted will still maintain the relationship but won't appear in property queries.

4. **Performance**: The favorites list query includes eager loading of relationships (images, neighborhood, features) to minimize database queries.

5. **Security**: All endpoints require authentication. Users can only manage their own favorites.
