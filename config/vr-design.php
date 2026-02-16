<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VR Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the VR technology provider for rendering 3D property designs.
    | Supported providers: 'threejs', 'babylonjs', 'aframe', 'mock'
    |
    */
    'provider' => env('VR_DESIGN_PROVIDER', 'mock'),

    /*
    |--------------------------------------------------------------------------
    | VR Providers Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for each VR provider
    |
    */
    'providers' => [
        'threejs' => [
            'enabled' => true,
            'cdn_url' => 'https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js',
            'version' => '0.160.0',
        ],
        'babylonjs' => [
            'enabled' => true,
            'cdn_url' => 'https://cdn.babylonjs.com/babylon.js',
            'version' => 'latest',
        ],
        'aframe' => [
            'enabled' => true,
            'cdn_url' => 'https://aframe.io/releases/1.4.2/aframe.min.js',
            'version' => '1.4.2',
        ],
        'mock' => [
            'enabled' => true,
            'description' => 'Mock provider for development and testing',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Design Styles
    |--------------------------------------------------------------------------
    |
    | Available interior design styles for VR customization
    |
    */
    'styles' => [
        'modern' => [
            'name' => 'Modern',
            'description' => 'Clean lines, minimal clutter, contemporary furniture',
            'color_palette' => ['#FFFFFF', '#000000', '#808080', '#C0C0C0'],
        ],
        'traditional' => [
            'name' => 'Traditional',
            'description' => 'Classic furniture, warm colors, timeless elegance',
            'color_palette' => ['#8B4513', '#DEB887', '#F5DEB3', '#CD853F'],
        ],
        'minimalist' => [
            'name' => 'Minimalist',
            'description' => 'Sparse furniture, simple decor, maximum space',
            'color_palette' => ['#FFFFFF', '#F5F5F5', '#E8E8E8', '#D3D3D3'],
        ],
        'luxury' => [
            'name' => 'Luxury',
            'description' => 'High-end furniture, elegant details, premium materials',
            'color_palette' => ['#FFD700', '#FFFFFF', '#000000', '#8B0000'],
        ],
        'industrial' => [
            'name' => 'Industrial',
            'description' => 'Exposed elements, metal, brick, urban aesthetic',
            'color_palette' => ['#696969', '#A9A9A9', '#8B4513', '#000000'],
        ],
        'scandinavian' => [
            'name' => 'Scandinavian',
            'description' => 'Light wood, cozy textiles, functional minimalism',
            'color_palette' => ['#FFFFFF', '#F5F5DC', '#D2B48C', '#87CEEB'],
        ],
        'contemporary' => [
            'name' => 'Contemporary',
            'description' => 'Current trends, bold accents, clean lines',
            'color_palette' => ['#FFFFFF', '#000000', '#FF6B6B', '#4ECDC4'],
        ],
        'rustic' => [
            'name' => 'Rustic',
            'description' => 'Natural materials, country charm, warm ambiance',
            'color_palette' => ['#8B4513', '#D2691E', '#F4A460', '#DEB887'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Furniture Categories
    |--------------------------------------------------------------------------
    |
    | Available furniture categories for VR design customization
    |
    */
    'furniture_categories' => [
        'seating' => ['Sofa', 'Armchair', 'Dining Chair', 'Bench', 'Ottoman'],
        'tables' => ['Dining Table', 'Coffee Table', 'Side Table', 'Desk', 'Console Table'],
        'storage' => ['Bookshelf', 'Cabinet', 'Wardrobe', 'Dresser', 'TV Stand'],
        'beds' => ['King Bed', 'Queen Bed', 'Single Bed', 'Bunk Bed'],
        'decor' => ['Rug', 'Artwork', 'Plant', 'Lamp', 'Mirror', 'Curtains'],
        'lighting' => ['Ceiling Light', 'Floor Lamp', 'Table Lamp', 'Wall Sconce'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Room Types
    |--------------------------------------------------------------------------
    |
    | Supported room types for VR design
    |
    */
    'room_types' => [
        'living_room' => 'Living Room',
        'bedroom' => 'Bedroom',
        'kitchen' => 'Kitchen',
        'bathroom' => 'Bathroom',
        'dining_room' => 'Dining Room',
        'office' => 'Office',
        'hallway' => 'Hallway',
        'balcony' => 'Balcony',
    ],

    /*
    |--------------------------------------------------------------------------
    | VR Device Compatibility
    |--------------------------------------------------------------------------
    |
    | Supported VR devices for immersive experience
    |
    */
    'supported_devices' => [
        'oculus_quest' => 'Meta Quest (Quest 2, Quest 3)',
        'htc_vive' => 'HTC Vive',
        'valve_index' => 'Valve Index',
        'psvr' => 'PlayStation VR',
        'windows_mr' => 'Windows Mixed Reality',
        'cardboard' => 'Google Cardboard',
        'browser' => 'WebXR-compatible browsers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Settings
    |--------------------------------------------------------------------------
    |
    | Settings for storing VR design files and thumbnails
    |
    */
    'storage' => [
        'disk' => env('VR_DESIGN_STORAGE_DISK', 'public'),
        'path' => 'vr-designs',
        'thumbnail_path' => 'vr-designs/thumbnails',
        'max_size' => 52428800, // 50MB in bytes
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    |
    | Settings to optimize VR rendering performance
    |
    */
    'performance' => [
        'max_polygons' => 100000,
        'texture_quality' => env('VR_TEXTURE_QUALITY', 'medium'), // low, medium, high
        'enable_shadows' => true,
        'enable_reflections' => true,
        'target_fps' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific VR features
    |
    */
    'features' => [
        'multiplayer_editing' => false,
        'ai_suggestions' => false,
        'virtual_walkthrough' => true,
        'measurement_tools' => true,
        'material_preview' => true,
        'lighting_simulation' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Settings for caching VR designs and scenes
    |
    */
    'cache' => [
        'enabled' => env('VR_DESIGN_CACHE_ENABLED', true),
        'ttl' => env('VR_DESIGN_CACHE_TTL', 3600), // 1 hour
        'prefix' => 'vr_design_',
    ],
];
