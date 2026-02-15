# Property Tax Estimator

This feature provides users with an accurate estimation of property taxes and additional costs associated with purchasing a property.

## Features

### Supported Countries

1. **United Kingdom**
   - Stamp Duty Land Tax (SDLT) calculations
   - Buyer type-specific rates (First Time Buyer, Home Mover, Additional Property)
   - Legal fees estimation
   - Survey fees estimation
   - Land Registry fees

2. **United States**
   - Transfer tax calculations
   - Annual property tax estimates
   - Recording fees
   - Title insurance estimation

3. **Other Countries**
   - Generic property transfer tax (3% default)
   - Legal fees estimation
   - Registration fees

## UK Stamp Duty Rates (2024)

### First Time Buyers
- Up to £300,000: 0%
- £300,001 - £500,000: 5%
- £500,001 - £925,000: 5%
- £925,001 - £1,500,000: 10%
- Over £1,500,000: 12%

### Home Movers
- Up to £250,000: 0%
- £250,001 - £925,000: 5%
- £925,001 - £1,500,000: 10%
- Over £1,500,000: 12%

### Additional Properties (Buy-to-Let / Second Homes)
- Up to £250,000: 3%
- £250,001 - £925,000: 8%
- £925,001 - £1,500,000: 13%
- Over £1,500,000: 15%

## Usage

### On Property Detail Pages

The property tax estimator is automatically displayed on property detail pages. Users can:

1. Select their buyer type (for UK properties)
2. Click "Calculate Estimated Taxes" to see results
3. View a detailed breakdown of all costs
4. See the total estimated cost including purchase price and all fees

### Programmatic Usage

```php
use App\Services\PropertyTaxEstimatorService;

$taxEstimator = app(PropertyTaxEstimatorService::class);

// UK property tax estimation
$result = $taxEstimator->estimatePropertyTax(
    300000, // Purchase price
    'UK',   // Country code
    ['buyer_type' => 'home_mover'] // Options
);

// Result includes:
// - stamp_duty: Calculated stamp duty
// - total_tax: Total tax amount
// - additional_costs: Legal fees, survey fees, etc.
// - total_cost: Grand total including purchase price
// - breakdown: Detailed cost breakdown
// - effective_tax_rate: Percentage of purchase price
```

## Testing

The feature includes comprehensive test coverage:

```bash
# Run all tests
php artisan test

# Run specific test suites
php artisan test tests/Unit/PropertyTaxEstimatorServiceTest.php
php artisan test tests/Unit/StampDutyCalculatorServiceTest.php
```

## Configuration

The estimator uses default values for various fees and rates. These can be customized by:

1. Modifying the service class methods
2. Adding configuration options to the options array
3. Creating a dedicated configuration file (future enhancement)

## Example Calculations

### Example 1: First Time Buyer - £250,000
- Stamp Duty: £0 (exempt up to £300k)
- Legal Fees: ~£1,200
- Survey Fees: ~£600
- Land Registry: £190
- **Total Cost: ~£252,000**

### Example 2: Home Mover - £400,000
- Stamp Duty: £7,500 (5% on £150k above £250k threshold)
- Legal Fees: ~£1,500
- Survey Fees: ~£900
- Land Registry: £270
- **Total Cost: ~£410,170**

### Example 3: Additional Property - £300,000
- Stamp Duty: £11,500 (includes 3% surcharge)
- Legal Fees: ~£1,200
- Survey Fees: ~£600
- Land Registry: £190
- **Total Cost: ~£313,500**

## Important Notes

- **These are estimates only** - Actual costs may vary
- Professional advice should be sought for accurate calculations
- Stamp duty rates are current as of 2024 and may change
- Additional costs are estimates based on typical market rates
- Consult with a solicitor or tax advisor for your specific situation

## Future Enhancements

Potential improvements:
- Add more countries and regions
- Support for different property types (residential vs commercial)
- Historical rate comparisons
- Save and share estimates
- Integration with mortgage calculators
- Regional variations (Scotland, Wales have different rates)
- Currency conversion for international buyers

## API Reference

### PropertyTaxEstimatorService

#### `estimatePropertyTax(float $purchasePrice, string $country, array $options): array`

Estimates property taxes for a given purchase price and country.

**Parameters:**
- `$purchasePrice` - The purchase price of the property
- `$country` - Country code (UK, US, etc.)
- `$options` - Array of options:
  - `buyer_type`: 'first_time_buyer', 'home_mover', 'additional_property' (UK only)
  - `annual_tax_rate`: Annual property tax rate (US only)
  - `transfer_tax_rate`: Transfer tax rate (US only)
  - `tax_rate`: Generic tax rate (other countries)

**Returns:**
Array containing:
- `country`: Country name
- `purchase_price`: Original purchase price
- `total_tax`: Total tax amount
- `total_additional_costs`: Sum of all additional costs
- `total_cost`: Grand total
- `breakdown`: Detailed cost breakdown
- `effective_tax_rate`: Tax as percentage of purchase price
- Additional country-specific fields

## Support

For issues or questions about the property tax estimator, please refer to the main repository documentation or open an issue on GitHub.
