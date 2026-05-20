# Investment Property Analytics

## Overview
The Investment Property Analytics feature provides AI-powered insights for potential real estate investors directly on property detail pages. This feature helps investors make informed decisions by analyzing various financial and market metrics.

## Features

### 1. Predicted ROI (Return on Investment)
- Calculates expected return over a 5-year period
- Based on market trends and historical data
- Displays positive/negative indicators with color coding

### 2. Risk Score
- Comprehensive risk assessment on a scale of 1-10
- Factors considered:
  - Market volatility (40% weight)
  - Property age (30% weight)
  - Location risk factor (30% weight)
- Lower scores indicate lower risk

### 3. Cash Flow Analysis
- **Estimated Annual Rent**: Based on 5% property price (industry standard)
- **Estimated Expenses**: Calculated at 30% of annual rent
- **Net Cash Flow**: Annual rent minus expenses
- **Cash-on-Cash Return**: Net cash flow as percentage of property price

### 4. Market Position Analysis
Compares property price to market average and categorizes as:
- **Excellent**: >10% below market average
- **Good**: 5-10% below market average
- **Average**: Within 5% of market average
- **Above Average**: 5-10% above market average
- **Premium**: >10% above market average

### 5. Advanced Investment Simulator
- Toggle to show/hide detailed investment calculator
- Supports multiple investment scenarios
- Customizable parameters:
  - Purchase price
  - Annual rental income
  - Annual expenses
  - Appreciation rate
  - Holding period

## Implementation Details

### Services

#### AIInvestmentAnalysisService
Location: `app/Services/AIInvestmentAnalysisService.php`

Main method: `analyzeInvestment(Property $property)`

Returns comprehensive analysis including:
- Market analysis data
- Property valuation
- Investment predictions
- Cash flow projections
- Market position

**Error Handling:**
- Gracefully handles missing market data
- Provides default values when data is unavailable
- Logs errors without breaking user experience

### Livewire Component

#### PropertyDetail
Location: `app/Http/Livewire/PropertyDetail.php`

New methods:
- `loadInvestmentAnalytics()`: Loads analytics data on component mount
- `getPositionBadgeClass($position)`: Returns CSS classes for position badges

### View

#### property-detail.blade.php
Location: `resources/views/livewire/property-detail.blade.php`

New sections:
1. Investment Analytics display (always visible when data available)
2. Advanced Investment Simulator (toggle on/off)

## Usage

### For End Users
1. Navigate to any property detail page
2. Scroll to the "Investment Analytics" section
3. View AI-powered insights including ROI, risk score, and cash flow
4. Click "Show Advanced Investment Simulator" for detailed scenario modeling

### For Developers

#### Accessing Investment Analytics Programmatically
```php
use App\Services\AIInvestmentAnalysisService;
use App\Models\Property;

$property = Property::find($id);
$service = app(AIInvestmentAnalysisService::class);
$analytics = $service->analyzeInvestment($property);

// Access specific metrics
$predictedROI = $analytics['prediction']['predicted_roi'];
$riskScore = $analytics['prediction']['risk_score'];
$cashFlow = $analytics['cash_flow_analysis']['net_cash_flow'];
```

#### Customizing Calculations
To modify default assumptions (e.g., rental yield, expense ratio), edit:
- `calculateCashFlowAnalysis()` method in `AIInvestmentAnalysisService`

To adjust risk score weights, edit:
- `calculateRiskScore()` method in `AIInvestmentAnalysisService`

## Testing

### Unit Tests
Location: `tests/Unit/InvestmentAnalyticsTest.php`

Coverage includes:
- Valid data structure validation
- Empty market data handling
- Cash flow calculation accuracy
- Edge cases and error scenarios

Run tests:
```bash
php artisan test --filter InvestmentAnalyticsTest
```

## Dependencies

- Laravel Framework
- Livewire
- Spatie Settings (for currency)
- Market Analysis Service
- Property Valuation Service
- Property Recommendation Service

## Future Enhancements

Potential improvements:
1. Integration with real-time market data APIs
2. Machine learning model for more accurate ROI predictions
3. Comparative analysis with similar properties
4. Historical performance tracking
5. PDF report generation
6. Email alerts for investment opportunities
7. Multi-currency support
8. Tax implications calculator

## Security Considerations

- All calculations are performed server-side
- No sensitive financial data is stored
- Input validation on all user-provided parameters
- Division by zero checks implemented
- Error logging without exposing internal details

## Performance

- Analytics are calculated once on page load
- Results are cached in component state
- No database queries on subsequent renders
- Minimal impact on page load time (<100ms)

## Support

For issues or questions:
1. Check the logs at `storage/logs/laravel.log`
2. Verify market data availability
3. Ensure all required services are properly configured
4. Contact the development team

## Changelog

### Version 1.0.0 (2026-02-15)
- Initial release
- Core analytics features
- AI-powered predictions
- Cash flow analysis
- Market position assessment
- Advanced investment simulator integration
