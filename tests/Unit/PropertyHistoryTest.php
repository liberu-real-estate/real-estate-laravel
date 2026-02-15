<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\PropertyHistory;
use App\Models\Team;
use App\Models\User;
use App\Services\PropertyHistoryService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected $historyService;
    protected $property;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->historyService = new PropertyHistoryService();
        
        // Create a user and team for testing
        $this->user = User::factory()->create();
        $team = Team::factory()->create();
        
        // Create a property
        $this->property = Property::factory()->create([
            'price' => 250000,
            'status' => 'available',
            'team_id' => $team->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_property_history_can_be_created()
    {
        $history = PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'listing',
            'description' => 'Property listed',
            'new_price' => 250000,
            'event_date' => now(),
        ]);

        $this->assertInstanceOf(PropertyHistory::class, $history);
        $this->assertEquals('listing', $history->event_type);
        $this->assertEquals($this->property->id, $history->property_id);
    }

    public function test_property_has_histories_relationship()
    {
        PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'listing',
            'description' => 'Property listed',
            'new_price' => 250000,
            'event_date' => now(),
        ]);

        $this->assertCount(1, $this->property->histories);
        $this->assertInstanceOf(PropertyHistory::class, $this->property->histories->first());
    }

    public function test_track_price_change()
    {
        $this->actingAs($this->user);
        
        $oldPrice = 250000;
        $newPrice = 275000;
        
        $history = $this->historyService->trackPriceChange($this->property, $oldPrice, $newPrice);

        $this->assertEquals('price_change', $history->event_type);
        $this->assertEquals($oldPrice, $history->old_price);
        $this->assertEquals($newPrice, $history->new_price);
        $this->assertStringContainsString('increased', $history->description);
    }

    public function test_track_status_change()
    {
        $this->actingAs($this->user);
        
        $history = $this->historyService->trackStatusChange($this->property, 'available', 'sold');

        $this->assertEquals('status_change', $history->event_type);
        $this->assertEquals('available', $history->old_status);
        $this->assertEquals('sold', $history->new_status);
        $this->assertStringContainsString('available', $history->description);
        $this->assertStringContainsString('sold', $history->description);
    }

    public function test_track_sale()
    {
        $this->actingAs($this->user);
        
        $salePrice = 265000;
        $history = $this->historyService->trackSale($this->property, $salePrice);

        $this->assertEquals('sale', $history->event_type);
        $this->assertEquals($salePrice, $history->new_price);
        $this->assertStringContainsString('sold', $history->description);
    }

    public function test_track_listing()
    {
        $this->actingAs($this->user);
        
        $listingPrice = 250000;
        $history = $this->historyService->trackListing($this->property, $listingPrice);

        $this->assertEquals('listing', $history->event_type);
        $this->assertEquals($listingPrice, $history->new_price);
        $this->assertStringContainsString('listed', $history->description);
    }

    public function test_get_price_change_percentage()
    {
        $history = PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'price_change',
            'description' => 'Price changed',
            'old_price' => 250000,
            'new_price' => 275000,
            'event_date' => now(),
        ]);

        $percentage = $history->getPriceChangePercentage();
        $this->assertEquals(10.0, $percentage);
    }

    public function test_get_price_change_percentage_for_decrease()
    {
        $history = PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'price_change',
            'description' => 'Price decreased',
            'old_price' => 250000,
            'new_price' => 225000,
            'event_date' => now(),
        ]);

        $percentage = $history->getPriceChangePercentage();
        $this->assertEquals(-10.0, $percentage);
    }

    public function test_scope_by_type()
    {
        PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'price_change',
            'description' => 'Price changed',
            'event_date' => now(),
        ]);

        PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'sale',
            'description' => 'Property sold',
            'event_date' => now(),
        ]);

        $priceChanges = PropertyHistory::byType('price_change')->get();
        $sales = PropertyHistory::byType('sale')->get();

        $this->assertCount(1, $priceChanges);
        $this->assertCount(1, $sales);
    }

    public function test_scope_price_changes()
    {
        PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'price_change',
            'description' => 'Price changed',
            'event_date' => now(),
        ]);

        PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'listing',
            'description' => 'Property listed',
            'event_date' => now(),
        ]);

        $priceChanges = PropertyHistory::priceChanges()->get();

        $this->assertCount(1, $priceChanges);
        $this->assertEquals('price_change', $priceChanges->first()->event_type);
    }

    public function test_scope_sales()
    {
        PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'sale',
            'description' => 'Property sold',
            'event_date' => now(),
        ]);

        PropertyHistory::create([
            'property_id' => $this->property->id,
            'event_type' => 'listing',
            'description' => 'Property listed',
            'event_date' => now(),
        ]);

        $sales = PropertyHistory::sales()->get();

        $this->assertCount(1, $sales);
        $this->assertEquals('sale', $sales->first()->event_type);
    }

    public function test_get_price_history_from_service()
    {
        $this->actingAs($this->user);
        
        $this->historyService->trackPriceChange($this->property, 250000, 275000);
        $this->historyService->trackPriceChange($this->property, 275000, 280000);

        $priceHistory = $this->historyService->getPriceHistory($this->property);

        $this->assertCount(2, $priceHistory);
    }

    public function test_get_sales_history_from_service()
    {
        $this->actingAs($this->user);
        
        $this->historyService->trackSale($this->property, 265000);

        $salesHistory = $this->historyService->getSalesHistory($this->property);

        $this->assertCount(1, $salesHistory);
    }
}
