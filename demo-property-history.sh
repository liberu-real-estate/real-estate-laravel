#!/bin/bash

# Property History Feature Demo Script
# This script demonstrates the property history tracking feature

echo "=================================="
echo "Property History Feature Demo"
echo "=================================="
echo ""

echo "Step 1: Run migrations..."
php artisan migrate --force

echo ""
echo "Step 2: Seed sample property history data..."
php artisan db:seed --class=PropertyHistorySeeder

echo ""
echo "Step 3: Test the property history service..."
php artisan tinker --execute="
\$property = App\Models\Property::first();
if (\$property) {
    echo 'Property: ' . \$property->title . PHP_EOL;
    echo 'Current Price: \$' . number_format(\$property->price, 2) . PHP_EOL;
    echo PHP_EOL;
    
    echo '--- Property History ---' . PHP_EOL;
    \$histories = \$property->histories()->take(5)->get();
    foreach (\$histories as \$history) {
        echo '[' . \$history->event_date->format('Y-m-d') . '] ';
        echo \$history->event_type . ': ';
        echo \$history->description . PHP_EOL;
    }
    
    echo PHP_EOL;
    echo '--- Price History ---' . PHP_EOL;
    \$priceHistory = \$property->histories()->priceChanges()->get();
    foreach (\$priceHistory as \$change) {
        echo '[' . \$change->event_date->format('Y-m-d') . '] ';
        echo '\$' . number_format(\$change->old_price, 2) . ' → \$' . number_format(\$change->new_price, 2);
        \$pct = \$change->getPriceChangePercentage();
        echo ' (' . (\$pct >= 0 ? '+' : '') . number_format(\$pct, 2) . '%)' . PHP_EOL;
    }
} else {
    echo 'No properties found. Please seed some properties first.' . PHP_EOL;
}
"

echo ""
echo "=================================="
echo "Demo completed!"
echo "=================================="
echo ""
echo "To view the property history in the UI:"
echo "1. Start the application: php artisan serve"
echo "2. Navigate to any property detail page"
echo "3. Scroll down to see the 'Property History' section"
echo ""
