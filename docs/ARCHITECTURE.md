# Interactive Floor Plans - Architecture Overview

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     INTERACTIVE FLOOR PLANS                      │
└─────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│                         ADMIN INTERFACE                           │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Filament Property Form (PropertyResource.php)           │   │
│  │  ┌────────────────────────────────────────────────────┐  │   │
│  │  │   FloorPlanEditor Component                        │  │   │
│  │  │   ├─ File Upload Input                             │  │   │
│  │  │   ├─ Interactive Canvas (Alpine.js)                │  │   │
│  │  │   │   └─ HTML5 Canvas API                          │  │   │
│  │  │   ├─ Tools (Add Room / Add Marker / Clear)         │  │   │
│  │  │   └─ Annotation List                               │  │   │
│  │  └────────────────────────────────────────────────────┘  │   │
│  │                          ↓                                │   │
│  │                     Save to DB                            │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
                                    ↓
┌──────────────────────────────────────────────────────────────────┐
│                          DATABASE                                 │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  properties table                                         │   │
│  │  ├─ floor_plan_data (JSON)                               │   │
│  │  │   {                                                    │   │
│  │  │     "image": "data:image/png;base64,...",             │   │
│  │  │     "annotations": [                                  │   │
│  │  │       {                                                │   │
│  │  │         "type": "room",                               │   │
│  │  │         "x": 100,                                     │   │
│  │  │         "y": 150,                                     │   │
│  │  │         "label": "Living Room"                        │   │
│  │  │       }                                                │   │
│  │  │     ]                                                  │   │
│  │  │   }                                                    │   │
│  │  └─ floor_plan_image (VARCHAR)                           │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
                                    ↓
┌──────────────────────────────────────────────────────────────────┐
│                       PROPERTY MODEL                              │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Property.php                                             │   │
│  │  ├─ fillable: ['floor_plan_data', 'floor_plan_image']    │   │
│  │  └─ casts: ['floor_plan_data' => 'array']                │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
                                    ↓
┌──────────────────────────────────────────────────────────────────┐
│                      FRONTEND INTERFACE                           │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Property Detail Page (property-detail.blade.php)        │   │
│  │  ┌────────────────────────────────────────────────────┐  │   │
│  │  │   FloorPlanViewer Component                        │  │   │
│  │  │   ├─ Interactive Canvas (Alpine.js)                │  │   │
│  │  │   │   └─ HTML5 Canvas API                          │  │   │
│  │  │   ├─ Click to View Details                         │  │   │
│  │  │   ├─ Hover Effects                                 │  │   │
│  │  │   ├─ Annotation Details Display                    │  │   │
│  │  │   └─ Legend                                         │  │   │
│  │  └────────────────────────────────────────────────────┘  │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘


## Data Flow

1. UPLOAD:  Admin uploads floor plan image → Canvas displays image
2. ANNOTATE: Admin clicks on canvas → Annotation added to state
3. SAVE:    Form submission → JSON saved to database
4. DISPLAY: Property page loads → Data retrieved from DB
5. RENDER:  Canvas draws image and annotations
6. INTERACT: User clicks/hovers → Details displayed


## Component Interaction

┌───────────────┐      ┌──────────────────┐      ┌─────────────┐
│ FloorPlan     │─────→│  Property Model  │─────→│  Database   │
│ Editor        │      │                  │      │             │
│ (Admin)       │      │  - fillable[]    │      │ floor_plan_ │
│               │      │  - casts[]       │      │ data (JSON) │
└───────────────┘      └──────────────────┘      └─────────────┘
                              ↓                          ↓
                       ┌──────────────────┐      ┌─────────────┐
                       │  Property        │←─────│  Query      │
                       │  Controller      │      │             │
                       └──────────────────┘      └─────────────┘
                              ↓
                       ┌──────────────────┐
                       │  FloorPlan       │
                       │  Viewer          │
                       │  (Frontend)      │
                       └──────────────────┘


## Technology Stack

┌────────────────────────────────────────────────────────────┐
│  Frontend                                                   │
│  ├─ Alpine.js (Reactive UI)              [Already in app]  │
│  ├─ HTML5 Canvas API (Drawing)           [Native browser]  │
│  ├─ Tailwind CSS (Styling)               [Already in app]  │
│  └─ Blade Components (Templates)         [Laravel native]  │
└────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────┐
│  Backend                                                    │
│  ├─ Filament PHP (Admin UI)               [Already in app] │
│  ├─ Laravel Eloquent (ORM)                [Laravel native] │
│  ├─ JSON Storage (Database)               [MySQL/Postgres] │
│  └─ Custom Components (FloorPlanEditor)   [Created]        │
└────────────────────────────────────────────────────────────┘


## File Structure

real-estate-laravel/
├── app/
│   ├── Models/
│   │   └── Property.php                          [Modified]
│   └── Filament/
│       ├── Forms/
│       │   └── Components/
│       │       └── FloorPlanEditor.php           [New]
│       └── Staff/
│           └── Resources/
│               └── Properties/
│                   └── PropertyResource.php      [Modified]
├── resources/
│   └── views/
│       ├── components/
│       │   └── floor-plan-viewer.blade.php       [New]
│       ├── filament/
│       │   └── forms/
│       │       └── components/
│       │           └── floor-plan-editor.blade.php [New]
│       └── livewire/
│           └── property-detail.blade.php         [Modified]
├── tests/
│   └── Unit/
│       └── FloorPlanTest.php                     [New]
├── database/
│   └── migrations/
│       └── 2024_02_13_000000_add_floor_plan_     [Existing]
│           fields_to_properties_table.php
└── docs/
    └── INTERACTIVE_FLOOR_PLANS.md                [New]


## Security Considerations

✅ Input Validation
   - File type validation (PNG, JPG, SVG only)
   - Base64 encoding for safe storage
   - JSON validation on save

✅ XSS Protection
   - Blade templating auto-escapes output
   - Alpine.js sanitizes user input
   - Canvas API prevents script injection

✅ Authorization
   - Filament Shield integration
   - Admin-only access to editor
   - Public read-only viewer

✅ Data Integrity
   - JSON schema validation
   - Type casting in Eloquent model
   - Database constraints


## Performance Considerations

✅ Efficient Storage
   - JSON column for flexible data
   - Base64 for small images
   - Optional separate image file support

✅ Frontend Optimization
   - Canvas caching
   - Lazy loading of floor plans
   - Responsive image scaling

✅ Minimal Dependencies
   - Uses existing Alpine.js
   - Native Canvas API
   - No external libraries needed
