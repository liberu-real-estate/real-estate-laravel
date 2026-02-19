# Holographic Property Tours - Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                        USER INTERFACE LAYER                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  ┌──────────────────────────┐      ┌──────────────────────────┐    │
│  │  Property Detail Page    │      │  Holographic Viewer Page │    │
│  │  (property-detail.blade) │      │  (holographic-viewer)    │    │
│  │                          │      │                          │    │
│  │  ┌──────────────────┐    │      │  ┌──────────────────┐   │    │
│  │  │ 3D Model Display │    │      │  │ Full 3D Viewer   │   │    │
│  │  └──────────────────┘    │      │  │ + Holographic FX │   │    │
│  │  ┌──────────────────┐    │      │  └──────────────────┘   │    │
│  │  │ Holographic Tour │────┼──────┼─>│ Device Selector  │   │    │
│  │  │ Section          │    │      │  │ Viewing Modes    │   │    │
│  │  │ - Generate       │    │      │  │ Property Info    │   │    │
│  │  │ - Launch         │    │      │  └──────────────────┘   │    │
│  │  └──────────────────┘    │      │                          │    │
│  └──────────────────────────┘      └──────────────────────────┘    │
│                                                                       │
└───────────────────────────┬─────────────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────────────┐
│                     LIVEWIRE COMPONENTS LAYER                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  ┌────────────────────────────────┐  ┌─────────────────────────┐   │
│  │  PropertyDetail Component      │  │ HolographicViewer       │   │
│  │                                │  │ Component               │   │
│  │  Properties:                   │  │                         │   │
│  │  - $holographicTourAvailable   │  │ Properties:             │   │
│  │  - $showHolographicViewer      │  │ - $property             │   │
│  │                                │  │ - $tourMetadata         │   │
│  │  Methods:                      │  │ - $selectedDevice       │   │
│  │  - checkHolographicTour...()   │  │ - $viewerMode           │   │
│  │  - toggleHolographicViewer()   │  │                         │   │
│  │  - generateHolographicTour()   │  │ Methods:                │   │
│  │                                │  │ - selectDevice()        │   │
│  └────────────┬───────────────────┘  │ - changeViewerMode()    │   │
│               │                      └───────┬─────────────────┘   │
│               │                              │                      │
└───────────────┼──────────────────────────────┼──────────────────────┘
                │                              │
┌───────────────▼──────────────────────────────▼──────────────────────┐
│                      SERVICE LAYER                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │           HolographicTourService                           │     │
│  │                                                            │     │
│  │  Core Methods:                                            │     │
│  │  ├─ generateHolographicTour(Property) → array|null       │     │
│  │  ├─ getHolographicTourUrl(Property) → string|null        │     │
│  │  ├─ isAvailable(Property) → bool                         │     │
│  │  ├─ getSupportedDevices() → array                        │     │
│  │  ├─ validateContent(array) → bool                        │     │
│  │  ├─ getMetadata(Property) → array|null                   │     │
│  │  ├─ updateConfiguration(Property, array) → bool          │     │
│  │  └─ disable(Property) → bool                             │     │
│  │                                                            │     │
│  │  Configuration:                                           │     │
│  │  - API Key                                                │     │
│  │  - Base URI                                               │     │
│  │  - Provider (Looking Glass, HoloFan, etc.)               │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
└───────────────────────┬───────────────────────────────────────────────┘
                        │
┌───────────────────────▼─────────────────────────────────────────────┐
│                      MODEL LAYER                                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │                   Property Model                           │     │
│  │                                                            │     │
│  │  New Fields:                                              │     │
│  │  - holographic_tour_url (string, nullable)                │     │
│  │  - holographic_provider (string, nullable)                │     │
│  │  - holographic_metadata (json, nullable)                  │     │
│  │  - holographic_enabled (boolean, default: false)          │     │
│  │                                                            │     │
│  │  Existing Relations:                                      │     │
│  │  - model_3d_url (for 3D models)                          │     │
│  │  - media (Spatie Media Library)                          │     │
│  │                                                            │     │
│  │  New Methods:                                             │     │
│  │  - hasHolographicTour() → bool                           │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
└───────────────────────┬───────────────────────────────────────────────┘
                        │
┌───────────────────────▼─────────────────────────────────────────────┐
│                    DATABASE LAYER                                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │              Properties Table                              │     │
│  │                                                            │     │
│  │  Columns:                                                 │     │
│  │  - id (primary key)                                       │     │
│  │  - title, description, price, etc. (existing fields)      │     │
│  │  - model_3d_url (existing)                                │     │
│  │  - holographic_tour_url ◄── NEW                          │     │
│  │  - holographic_provider ◄── NEW                          │     │
│  │  - holographic_metadata ◄── NEW (JSON)                   │     │
│  │  - holographic_enabled ◄── NEW (BOOLEAN)                 │     │
│  │  - created_at, updated_at                                 │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                    EXTERNAL INTEGRATIONS                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  ┌──────────────────┐  ┌──────────────────┐  ┌─────────────────┐   │
│  │ Google Model     │  │ Holographic      │  │ Spatie Media    │   │
│  │ Viewer (CDN)     │  │ Provider APIs    │  │ Library         │   │
│  │                  │  │                  │  │                 │   │
│  │ - 3D Rendering   │  │ - Looking Glass  │  │ - 3D Model      │   │
│  │ - AR Support     │  │ - HoloFan        │  │   Storage       │   │
│  │ - Web Component  │  │ - HoloLamp       │  │ - File Upload   │   │
│  └──────────────────┘  └──────────────────┘  └─────────────────┘   │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                     CACHING LAYER                                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  Cache Key Pattern: "holographic_tour_{property_id}"                │
│  Expiration: 7 days                                                  │
│  Storage: Laravel Cache (configurable: Redis, Memcached, etc.)      │
│                                                                       │
│  Cached Data:                                                        │
│  - Tour metadata (property_id, model_url, display_type, etc.)       │
│  - Device configurations                                             │
│  - Viewing settings                                                  │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════
                        DATA FLOW DIAGRAM
═══════════════════════════════════════════════════════════════════════

User Action: "Generate Holographic Tour"
│
├─> PropertyDetail Component
│   └─> generateHolographicTour()
│       │
│       ├─> HolographicTourService
│       │   └─> generateHolographicTour(Property)
│       │       │
│       │       ├─> Check for 3D model
│       │       │   └─> Property.model_3d_url or Media Library
│       │       │
│       │       ├─> Generate tour metadata
│       │       │   └─> {property_id, model_url, display_type, etc.}
│       │       │
│       │       ├─> Cache tour data (7 days)
│       │       │   └─> Cache::put("holographic_tour_{id}", data, 7 days)
│       │       │
│       │       └─> Return tour data
│       │
│       └─> getHolographicTourUrl(Property)
│           │
│           ├─> Generate route URL
│           │   └─> route('property.holographic-tour', $property)
│           │
│           └─> Update Property model
│               └─> Update fields: holographic_tour_url,
│                   holographic_provider, holographic_metadata,
│                   holographic_enabled
│
└─> Response: Tour URL generated
    └─> UI updates: Show "Launch Holographic Tour" button

═══════════════════════════════════════════════════════════════════════

User Action: "Launch Holographic Tour"
│
├─> Navigate to: /properties/{id}/holographic-tour
│
├─> HolographicViewer Component loads
│   │
│   ├─> Fetch Property data
│   ├─> Fetch tour metadata (from cache or database)
│   ├─> Load supported devices list
│   └─> Initialize viewer state
│
├─> Render Holographic Viewer Page
│   │
│   ├─> Display 3D Model (Google Model Viewer)
│   │   └─> Load model from Property.model_3d_url
│   │
│   ├─> Apply holographic effects (CSS overlays)
│   │
│   ├─> Render device selector
│   │   └─> Looking Glass, HoloFan, HoloLamp, Web Viewer
│   │
│   └─> Render property information sidebar
│
└─> User interacts with viewer
    ├─> Rotate/Zoom model (interactive mode)
    ├─> Switch devices (selectDevice())
    ├─> Change viewing modes (changeViewerMode())
    └─> View property details

═══════════════════════════════════════════════════════════════════════
                     SUPPORTED DEVICES
═══════════════════════════════════════════════════════════════════════

┌───────────────────┬──────────────┬────────────────┬─────────────────┐
│ Device            │ Resolution   │ Viewing Angle  │ Status          │
├───────────────────┼──────────────┼────────────────┼─────────────────┤
│ Looking Glass     │ 1536x2048    │ 40°            │ Supported       │
│ Portrait          │              │                │                 │
├───────────────────┼──────────────┼────────────────┼─────────────────┤
│ Looking Glass Pro │ 4096x4096    │ 50°            │ Supported       │
├───────────────────┼──────────────┼────────────────┼─────────────────┤
│ HoloFan           │ 1920x1080    │ 360°           │ Supported       │
├───────────────────┼──────────────┼────────────────┼─────────────────┤
│ HoloLamp          │ 2560x1440    │ 180°           │ Supported       │
├───────────────────┼──────────────┼────────────────┼─────────────────┤
│ Web Viewer        │ Adaptive     │ Interactive    │ Active (Primary)│
└───────────────────┴──────────────┴────────────────┴─────────────────┘

═══════════════════════════════════════════════════════════════════════
                  CONFIGURATION FLOW
═══════════════════════════════════════════════════════════════════════

Environment Variables (.env)
    │
    ├─> HOLOGRAPHIC_PROVIDER=looking_glass
    ├─> HOLOGRAPHIC_API_KEY=your_api_key_here
    ├─> HOLOGRAPHIC_BASE_URI=https://api.lookingglassfactory.com
    └─> HOLOGRAPHIC_WEB_VIEWER=true
        │
        ▼
Config (config/services.php)
    │
    └─> 'holographic' => [...]
        │
        ▼
HolographicTourService
    │
    ├─> $this->apiKey
    ├─> $this->baseUri
    └─> $this->provider
        │
        ▼
Tour Generation & Management

═══════════════════════════════════════════════════════════════════════
```

## Key Integration Points

1. **Property Model ↔ 3D Models**: Uses existing Spatie Media Library integration
2. **Livewire Components ↔ Service**: Clean separation of concerns
3. **Caching Layer**: Reduces database load, improves performance
4. **External APIs**: Placeholder for future holographic provider integrations
5. **UI/UX**: Seamless integration with existing property detail pages

## Security Architecture

```
┌─────────────────────────────────────────────────┐
│  Route Middleware (Optional)                    │
│  - Auth                                         │
│  - Permission checks                            │
└─────────────────┬───────────────────────────────┘
                  │
┌─────────────────▼───────────────────────────────┐
│  Input Validation                               │
│  - Property exists                              │
│  - User has access                              │
│  - Metadata structure validation                │
└─────────────────┬───────────────────────────────┘
                  │
┌─────────────────▼───────────────────────────────┐
│  Service Layer Processing                       │
│  - Sanitize inputs                              │
│  - Validate 3D model URLs                       │
│  - Check file permissions                       │
└─────────────────┬───────────────────────────────┘
                  │
┌─────────────────▼───────────────────────────────┐
│  Database Operations                            │
│  - Prepared statements (Eloquent)               │
│  - Transaction support                          │
└─────────────────────────────────────────────────┘
```

## Performance Optimization Flow

```
Request → Check Cache → Cache Hit? 
                           │
                    ┌──────┴──────┐
                    │             │
                   Yes            No
                    │             │
             Return cached     Generate
                data          tour data
                    │             │
                    │      ┌──────▼──────┐
                    │      │ Store in    │
                    │      │ Cache       │
                    │      └──────┬──────┘
                    │             │
                    └──────┬──────┘
                           │
                    Return to user
```

This architecture provides a solid foundation for holographic property tours while maintaining clean code principles and Laravel best practices.
