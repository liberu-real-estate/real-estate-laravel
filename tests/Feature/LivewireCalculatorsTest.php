<?php

namespace Tests\Feature;

use App\Livewire\CalculatorsComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireCalculatorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculators_component_renders(): void
    {
        Livewire::test(CalculatorsComponent::class)
            ->assertStatus(200);
    }

    public function test_can_calculate_mortgage(): void
    {
        Livewire::test(CalculatorsComponent::class)
            ->set('propertyPrice', 200000)
            ->set('loanAmount', 160000)
            ->set('interestRate', 3.5)
            ->set('loanTerm', 25)
            ->call('calculateMortgage')
            ->assertHasNoErrors(['propertyPrice', 'loanAmount', 'interestRate', 'loanTerm']);
    }

    public function test_mortgage_validation_requires_numeric_values(): void
    {
        Livewire::test(CalculatorsComponent::class)
            ->set('propertyPrice', 'not-a-number')
            ->set('loanAmount', null)
            ->set('interestRate', '')
            ->set('loanTerm', 'abc')
            ->call('calculateMortgage')
            ->assertHasErrors(['propertyPrice', 'loanAmount', 'interestRate', 'loanTerm']);
    }

    public function test_can_calculate_cost_of_moving(): void
    {
        Livewire::test(CalculatorsComponent::class)
            ->set('propertyValue', 250000)
            ->set('isFirstTimeBuyer', true)
            ->set('movingDistance', 50)
            ->call('calculateCostOfMoving')
            ->assertHasNoErrors(['propertyValue', 'isFirstTimeBuyer', 'movingDistance']);
    }

    public function test_can_calculate_rental_yield(): void
    {
        Livewire::test(CalculatorsComponent::class)
            ->set('rentalPropertyValue', 200000)
            ->set('annualRentalIncome', 12000)
            ->set('annualExpenses', 2000)
            ->call('calculateRentalYield')
            ->assertHasNoErrors(['rentalPropertyValue', 'annualRentalIncome', 'annualExpenses']);
    }

    public function test_default_calculator_type_is_mortgage(): void
    {
        Livewire::test(CalculatorsComponent::class)
            ->assertSet('calculatorType', 'mortgage');
    }
}
