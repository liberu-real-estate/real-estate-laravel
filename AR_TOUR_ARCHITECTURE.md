# AR Property Tours - Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                     AR Property Tours System                     │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         Frontend Layer                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  Property Detail Page (Blade + Livewire)              │    │
│  │  - AR availability badge                               │    │
│  │  - Google Model Viewer component                       │    │
│  │  - AR configuration from backend                       │    │
│  │  - User instructions panel                             │    │
│  └────────────────────────────────────────────────────────┘    │
│                            ↓ ↑                                   │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  PropertyDetail Livewire Component                     │    │
│  │  - $arTourAvailable (boolean)                          │    │
│  │  - $arTourConfig (array)                               │    │
│  │  - loadARTourData() method                             │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
                            ↓ ↑
┌─────────────────────────────────────────────────────────────────┐
│                       API/Controller Layer                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  ARTourController                                      │    │
│  │                                                         │    │
│  │  Public Endpoints:                                     │    │
│  │  • GET  /properties/{id}/ar-tour/config               │    │
│  │  • GET  /properties/{id}/ar-tour/availability          │    │
│  │                                                         │    │
│  │  Authenticated Endpoints:                              │    │
│  │  • POST /properties/{id}/ar-tour/enable                │    │
│  │  • POST /properties/{id}/ar-tour/disable               │    │
│  │  • PUT  /properties/{id}/ar-tour/settings              │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
                            ↓ ↑
┌─────────────────────────────────────────────────────────────────┐
│                        Service Layer                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  ARTourService                                         │    │
│  │                                                         │    │
│  │  Methods:                                              │    │
│  │  • isARTourAvailable(Property): bool                   │    │
│  │  • getARTourConfig(Property): array                    │    │
│  │  • enableARTour(Property, array): bool                 │    │
│  │  • disableARTour(Property): bool                       │    │
│  │  • updateARTourSettings(Property, array): bool         │    │
│  │  • validate3DModel(string): array                      │    │
│  │  • getARTourStats(Property): array                     │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
                            ↓ ↑
┌─────────────────────────────────────────────────────────────────┐
│                          Data Layer                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  Property Model                                        │    │
│  │                                                         │    │
│  │  AR Tour Fields:                                       │    │
│  │  • ar_tour_enabled (boolean)                           │    │
│  │  • ar_tour_settings (json)                             │    │
│  │  • ar_placement_guide (string)                         │    │
│  │  • ar_model_scale (float)                              │    │
│  │                                                         │    │
│  │  Relations:                                            │    │
│  │  • hasMedia('3d_models')                               │    │
│  └────────────────────────────────────────────────────────┘    │
│                            ↓ ↑                                   │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  Database (properties table)                           │    │
│  │  + Spatie Media Library (3d_models collection)         │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                       Admin Panel (Filament)                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  PropertyResource                                      │    │
│  │                                                         │    │
│  │  Form Fields:                                          │    │
│  │  • Toggle: Enable AR Tour                              │    │
│  │  • TextInput: AR Model Scale (0.1-10)                  │    │
│  │  • Select: AR Placement Guide (floor/wall/ceiling)     │    │
│  │                                                         │    │
│  │  Table Columns:                                        │    │
│  │  • IconColumn: AR Tour Status                          │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                      External Services                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  • Google Model Viewer (3D/AR rendering)                        │
│  • ARCore (Android AR platform)                                 │
│  • ARKit (iOS AR platform)                                      │
│  • WebXR (Web-based AR)                                         │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                      Data Flow Examples                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  User Views Property with AR:                                   │
│  1. User navigates to property detail page                      │
│  2. PropertyDetail component loads property                     │
│  3. loadARTourData() calls ARTourService                        │
│  4. ARTourService checks property.ar_tour_enabled               │
│  5. ARTourService checks hasMedia('3d_models')                  │
│  6. Returns AR config if available                              │
│  7. Blade view renders model-viewer with AR attributes          │
│  8. User taps AR button on mobile                               │
│  9. Native AR viewer launches (ARCore/ARKit)                    │
│  10. User experiences property in AR                            │
│                                                                  │
│  Admin Enables AR Tour:                                         │
│  1. Admin opens property in Filament                            │
│  2. Uploads 3D model (.glb or .gltf)                            │
│  3. Toggles "Enable AR Tour" to ON                              │
│  4. Sets AR model scale (e.g., 1.5)                             │
│  5. Selects placement guide (e.g., floor)                       │
│  6. Saves property                                              │
│  7. Migration adds AR fields to properties table                │
│  8. Property model persists AR configuration                    │
│  9. AR tour is now available for users                          │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                      Technology Stack                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Backend:                                                       │
│  • Laravel 10.x (PHP Framework)                                 │
│  • Livewire 3.x (Reactive Components)                           │
│  • Filament 3.x (Admin Panel)                                   │
│  • Spatie Media Library (File Management)                       │
│                                                                  │
│  Frontend:                                                      │
│  • Blade (Templating)                                           │
│  • Tailwind CSS (Styling)                                       │
│  • Alpine.js (Interactivity)                                    │
│  • Google Model Viewer (3D/AR)                                  │
│                                                                  │
│  3D/AR:                                                         │
│  • GLB/GLTF (3D Model Formats)                                  │
│  • ARCore (Android)                                             │
│  • ARKit (iOS)                                                  │
│  • WebXR (Web AR)                                               │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

## Key Integration Points

1. **Property Model ↔ ARTourService**: Service layer accesses AR fields via Eloquent model
2. **ARTourController ↔ ARTourService**: Controller delegates business logic to service
3. **PropertyDetail ↔ ARTourService**: Livewire component uses service for AR data
4. **Blade View ↔ Model Viewer**: Frontend renders AR using Google's web component
5. **Filament ↔ Property Model**: Admin panel manages AR fields via Eloquent

## Security Layers

- **Authentication**: Required for enable/disable/update endpoints
- **Authorization**: Property ownership/team access checks
- **Validation**: Input validation on all API endpoints
- **File Validation**: 3D model format and size checks
- **XSS Protection**: Blade escaping for all user inputs
- **SQL Injection**: Eloquent ORM parameterized queries

## Performance Optimizations

- **Lazy Loading**: AR config loaded only when needed
- **Caching**: AR settings stored in JSON field
- **Media Library**: Optimized file storage and retrieval
- **File Size**: Validation recommends <10MB for mobile
- **Efficient Queries**: Only fetches required data

## Browser/Device Support Matrix

| Platform | Browser/OS | AR Platform | Status |
|----------|-----------|-------------|---------|
| Android | Chrome 79+ | ARCore | ✅ Supported |
| iOS | Safari 13+ | ARKit | ✅ Supported |
| Desktop | Chrome/Edge 79+ | WebXR | ✅ Supported |
| Desktop | Firefox 70+ | WebXR | ✅ Supported |
| Mobile | Older browsers | N/A | ⚠️ 3D only, no AR |

## Future Architecture Enhancements

1. **Analytics Layer**: Track AR tour usage, duration, interactions
2. **CDN Integration**: Serve 3D models from CDN for better performance
3. **Compression**: Implement Draco compression for 3D models
4. **Caching**: Redis cache for frequently accessed AR configs
5. **Queue System**: Background processing for 3D model optimization
6. **Event System**: AR tour events for CRM integration
7. **WebSocket**: Real-time collaborative AR tours
8. **ML Integration**: AI-powered AR recommendations
