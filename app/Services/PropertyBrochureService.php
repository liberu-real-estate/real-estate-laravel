<?php

namespace App\Services;

use App\Models\Property;
use InvalidArgumentException;

class PropertyBrochureService
{
    /**
     * Generate brochure data for a property.
     * Returns structured data that can be rendered to PDF or HTML.
     *
     * @param  Property  $property
     * @param  array  $options
     * @return array
     */
    public function generateBrochureData(Property $property, array $options = []): array
    {
        $includeFloorPlan = $options['include_floor_plan'] ?? true;
        $includeMap = $options['include_map'] ?? true;
        $includeEpc = $options['include_epc'] ?? true;
        $template = $options['template'] ?? 'standard';

        $features = $property->features()->get()->pluck('feature')->toArray();
        $images = $property->getMedia('images')->map(fn ($m) => $m->getUrl())->toArray();

        return [
            'template' => $template,
            'property' => [
                'id' => $property->id,
                'title' => $property->title,
                'description' => $property->description,
                'location' => $property->location,
                'price' => $property->price,
                'formatted_price' => '£' . number_format($property->price, 0),
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'area_sqft' => $property->area_sqft,
                'property_type' => $property->property_type,
                'status' => $property->status,
                'year_built' => $property->year_built,
                'energy_rating' => $property->energy_rating,
                'energy_score' => $property->energy_score,
                'features' => $features,
                'images' => $images,
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
            ],
            'options' => [
                'include_floor_plan' => $includeFloorPlan,
                'include_map' => $includeMap,
                'include_epc' => $includeEpc,
            ],
            'generated_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Generate an HTML brochure for a property.
     *
     * @param  Property  $property
     * @param  array  $options
     * @return string  HTML content
     */
    public function generateHtmlBrochure(Property $property, array $options = []): string
    {
        $data = $this->generateBrochureData($property, $options);
        $p = $data['property'];

        $featuresHtml = implode('', array_map(
            fn ($f) => '<li>' . htmlspecialchars($f, ENT_QUOTES, 'UTF-8') . '</li>',
            $p['features']
        ));

        $imagesHtml = implode('', array_map(
            fn ($img) => '<img src="' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . '" style="max-width:100%;margin-bottom:10px;" />',
            array_slice($p['images'], 0, 6)
        ));

        $epcHtml = ($data['options']['include_epc'] && $p['energy_rating'])
            ? "<p><strong>EPC Rating:</strong> {$p['energy_rating']} ({$p['energy_score']})</p>"
            : '';

        $title = htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8');
        $location = htmlspecialchars($p['location'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($p['description'] ?? '', ENT_QUOTES, 'UTF-8');

        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"><title>{$title} - Property Brochure</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
            h1 { color: #1a1a2e; } .price { font-size: 1.5em; color: #e94560; font-weight: bold; }
            .details { display: flex; gap: 20px; margin: 10px 0; }
            .detail { background: #f5f5f5; padding: 8px 16px; border-radius: 4px; }
            ul { columns: 2; } .images { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        </style>
        </head>
        <body>
            <h1>{$title}</h1>
            <p class="price">{$p['formatted_price']}</p>
            <p><strong>Location:</strong> {$location}</p>
            <div class="details">
                <div class="detail">🛏 {$p['bedrooms']} Bedrooms</div>
                <div class="detail">🚿 {$p['bathrooms']} Bathrooms</div>
                <div class="detail">📐 {$p['area_sqft']} sq ft</div>
                <div class="detail">🏠 {$p['property_type']}</div>
            </div>
            {$epcHtml}
            <h2>Description</h2>
            <p>{$description}</p>
            <h2>Features</h2>
            <ul>{$featuresHtml}</ul>
            <h2>Photos</h2>
            <div class="images">{$imagesHtml}</div>
            <p style="margin-top:40px;font-size:0.8em;color:#999;">Generated on {$data['generated_at']}</p>
        </body>
        </html>
        HTML;
    }

    /**
     * Generate a window card (small display card for office window).
     *
     * @param  Property  $property
     * @return string  HTML content
     */
    public function generateWindowCard(Property $property): string
    {
        $title = htmlspecialchars($property->title, ENT_QUOTES, 'UTF-8');
        $location = htmlspecialchars($property->location ?? '', ENT_QUOTES, 'UTF-8');
        $price = '£' . number_format($property->price, 0);
        $images = $property->getMedia('images');
        $heroImage = $images->isNotEmpty() ? $images->first()->getUrl() : '';

        $heroHtml = $heroImage
            ? '<img src="' . htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8') . '" style="width:100%;height:180px;object-fit:cover;" />'
            : '<div style="width:100%;height:180px;background:#e0e0e0;display:flex;align-items:center;justify-content:center;">No Image</div>';

        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"><title>Window Card</title>
        <style>
            body { margin: 0; font-family: Arial, sans-serif; }
            .card { width: 320px; border: 2px solid #1a1a2e; overflow: hidden; page-break-after: always; }
            .card-body { padding: 12px; }
            .price { font-size: 1.4em; font-weight: bold; color: #e94560; margin: 6px 0; }
            .title { font-size: 1em; font-weight: bold; color: #1a1a2e; }
            .details { font-size: 0.85em; color: #555; margin-top: 4px; }
        </style>
        </head>
        <body>
            <div class="card">
                {$heroHtml}
                <div class="card-body">
                    <div class="price">{$price}</div>
                    <div class="title">{$title}</div>
                    <div class="details">
                        {$location}<br>
                        🛏 {$property->bedrooms} bed &nbsp; 🚿 {$property->bathrooms} bath &nbsp; 📐 {$property->area_sqft} sq ft
                    </div>
                </div>
            </div>
        </body>
        </html>
        HTML;
    }
}
