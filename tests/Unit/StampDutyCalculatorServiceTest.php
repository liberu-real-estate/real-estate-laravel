<?php

namespace Tests\Unit;

use App\Services\StampDutyCalculatorService;
use Tests\TestCase;

class StampDutyCalculatorServiceTest extends TestCase
{
    protected StampDutyCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StampDutyCalculatorService();
    }

    /** @test */
    public function it_calculates_zero_stamp_duty_for_first_time_buyer_under_300k()
    {
        $result = $this->service->calculateStampDuty(250000, 'first_time_buyer');
        
        $this->assertEquals(0, $result['stamp_duty']);
        $this->assertEquals(0, $result['effective_tax_rate']);
    }

    /** @test */
    public function it_calculates_stamp_duty_for_first_time_buyer_between_300k_and_500k()
    {
        $result = $this->service->calculateStampDuty(400000, 'first_time_buyer');
        
        // £0 on first £300k, 5% on £100k = £5,000
        $this->assertEquals(5000, $result['stamp_duty']);
        $this->assertEquals(1.25, $result['effective_tax_rate']); // 5000/400000 * 100
    }

    /** @test */
    public function it_calculates_stamp_duty_for_home_mover_under_250k()
    {
        $result = $this->service->calculateStampDuty(200000, 'home_mover');
        
        $this->assertEquals(0, $result['stamp_duty']);
        $this->assertEquals(0, $result['effective_tax_rate']);
    }

    /** @test */
    public function it_calculates_stamp_duty_for_home_mover_at_300k()
    {
        $result = $this->service->calculateStampDuty(300000, 'home_mover');
        
        // £0 on first £250k, 5% on £50k = £2,500
        $this->assertEquals(2500, $result['stamp_duty']);
    }

    /** @test */
    public function it_calculates_stamp_duty_for_home_mover_at_500k()
    {
        $result = $this->service->calculateStampDuty(500000, 'home_mover');
        
        // £0 on first £250k, 5% on £250k = £12,500
        $this->assertEquals(12500, $result['stamp_duty']);
    }

    /** @test */
    public function it_calculates_stamp_duty_for_home_mover_over_925k()
    {
        $result = $this->service->calculateStampDuty(1000000, 'home_mover');
        
        // £0 on first £250k
        // 5% on £250k-£925k (£675k) = £33,750
        // 10% on £925k-£1m (£75k) = £7,500
        // Total = £41,250
        $this->assertEquals(41250, $result['stamp_duty']);
    }

    /** @test */
    public function it_calculates_stamp_duty_for_additional_property_under_250k()
    {
        $result = $this->service->calculateStampDuty(200000, 'additional_property');
        
        // 3% on £200k = £6,000
        $this->assertEquals(6000, $result['stamp_duty']);
    }

    /** @test */
    public function it_calculates_stamp_duty_for_additional_property_at_300k()
    {
        $result = $this->service->calculateStampDuty(300000, 'additional_property');
        
        // 3% on first £250k = £7,500
        // 8% on £50k = £4,000
        // Total = £11,500
        $this->assertEquals(11500, $result['stamp_duty']);
    }

    /** @test */
    public function it_calculates_stamp_duty_for_additional_property_at_500k()
    {
        $result = $this->service->calculateStampDuty(500000, 'additional_property');
        
        // 3% on first £250k = £7,500
        // 8% on £250k = £20,000
        // Total = £27,500
        $this->assertEquals(27500, $result['stamp_duty']);
    }

    /** @test */
    public function it_throws_exception_for_invalid_buyer_type()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid buyer type');
        
        $this->service->calculateStampDuty(300000, 'invalid_buyer');
    }

    /** @test */
    public function it_calculates_effective_tax_rate_correctly()
    {
        $result = $this->service->calculateStampDuty(400000, 'home_mover');
        
        // £0 on first £250k, 5% on £150k = £7,500
        $this->assertEquals(7500, $result['stamp_duty']);
        $this->assertEquals(1.88, $result['effective_tax_rate']); // 7500/400000 * 100 = 1.875 rounded to 1.88
    }

    /** @test */
    public function it_handles_zero_purchase_price()
    {
        $result = $this->service->calculateStampDuty(0, 'home_mover');
        
        $this->assertEquals(0, $result['stamp_duty']);
        $this->assertEquals(0, $result['effective_tax_rate']);
    }

    /** @test */
    public function it_calculates_stamp_duty_for_very_high_value_property()
    {
        $result = $this->service->calculateStampDuty(2000000, 'home_mover');
        
        // £0 on first £250k
        // 5% on £250k-£925k (£675k) = £33,750
        // 10% on £925k-£1.5m (£575k) = £57,500
        // 12% on £1.5m-£2m (£500k) = £60,000
        // Total = £151,250
        $this->assertEquals(151250, $result['stamp_duty']);
    }
}
