# Neural Network-Based Property Valuation

## Overview

This feature implements an AI-powered property valuation system using neural network algorithms to provide highly accurate property valuations based on multiple factors and historical data.

## Features

### 1. Neural Network Valuation Service
- **Feature Extraction**: Automatically extracts relevant features from property data including:
  - Property characteristics (bedrooms, bathrooms, area, age)
  - Location factors (latitude, longitude)
  - Property type encoding
  - Market factors (days on market, price per sqft)
  
- **Prediction Model**: Simulated neural network that:
  - Uses weighted features to calculate property values
  - Applies activation functions for non-linear predictions
  - Includes market adjustment factors
  
- **Confidence Scoring**: Calculates confidence levels (0-100%) based on:
  - Data completeness
  - Property age and characteristics
  - Available training data
  
- **Feature Importance**: Shows which factors most influence the valuation
- **Market Trends**: Analyzes market trends for the property location
- **Continuous Learning**: Model can be retrained with new data

### 2. API Endpoints

- **POST** `/properties/{property}/valuation/generate` - Generate new valuation
- **GET** `/properties/{property}/valuation/history` - Get valuation history
- **GET** `/properties/{property}/valuation/report` - Get detailed report
- **POST** `/valuation/train-model` - Train the neural network model (admin only)

### 3. User Interface

#### Property Valuation Page
Access via: `/properties/{propertyId}/valuation`

Features:
- Property summary display
- One-click AI valuation generation
- Confidence level visualization
- Feature importance charts
- Market insights and trends
- Valuation history table
- Detailed prediction factors

#### Integration
- "AI Valuation" button added to property detail pages
- Seamless navigation between property details and valuation

## Usage

### Generating a Valuation

1. Navigate to a property detail page
2. Click the "AI Valuation" button
3. On the valuation page, click "Generate AI Valuation"
4. View the comprehensive valuation report including:
   - Estimated market value
   - Confidence level
   - Value factors
   - Market trend
   - Comparable properties count

### Viewing Valuation History

The valuation history section shows all previous valuations for the property with:
- Valuation date
- Estimated value
- Confidence level
- Status (active/superseded)
- Quick access to view details

### Understanding the Report

**Estimated Market Value**: The AI-predicted value of the property

**Confidence Level**: How confident the model is in its prediction (0-100%)
- High (90%+): Very reliable prediction
- Medium (70-90%): Good prediction
- Low (<70%): Less reliable, may need more data

**Top Value Factors**: The features that most influenced the valuation with their importance percentages

**Prediction Insights**: Specific factors that affected the valuation (e.g., "Large property size adds significant value")

**Market Trend**: Current market trend for similar properties (rising, stable, declining, volatile)

## Technical Implementation

### Service Layer
- `NeuralNetworkValuationService`: Core service implementing the neural network logic
- `PropertyValuationService`: Base valuation service for comparable properties

### Controllers
- `PropertyValuationController`: Handles API requests for valuations

### Livewire Components
- `PropertyValuationComponent`: Interactive UI component for generating and viewing valuations

### Database
- Uses existing `property_valuations` table
- Stores:
  - Valuation type: 'neural_network'
  - Estimated value and confidence level
  - Feature importance in `comparable_properties` JSON field
  - Market insights in `location_factors` JSON field

### Model Versioning
- Current model version: 1.0.0
- Tracked in valuation notes for reproducibility

## Future Enhancements

1. **Actual ML Integration**: Replace simulated neural network with real ML models (TensorFlow, PyTorch)
2. **Training Pipeline**: Implement actual model training with historical transaction data
3. **Feature Engineering**: Add more sophisticated features (crime rates, school ratings, etc.)
4. **Ensemble Methods**: Combine multiple models for better accuracy
5. **Real-time Updates**: Automatically update valuations when market conditions change
6. **PDF Reports**: Generate downloadable PDF valuation reports
7. **Batch Valuations**: Value multiple properties at once
8. **API Access**: RESTful API for third-party integrations

## Testing

Comprehensive test suite includes:
- Valuation generation tests
- Confidence calculation tests
- Feature importance tests
- API endpoint tests
- Livewire component tests
- Authentication tests

Run tests:
```bash
php artisan test --filter NeuralNetworkValuationTest
```

## Security

- Authentication required for generating valuations
- Admin-only access for model training
- Rate limiting on API endpoints (recommended)
- Input validation and sanitization
- SQL injection protection via Eloquent ORM

## Performance Considerations

- Model weights cached for 1 hour
- Comparable properties limited to 10 for performance
- Database indexes on property_id and valuation_type
- Lazy loading of related data

## API Response Examples

### Generate Valuation Response
```json
{
  "success": true,
  "valuation": {
    "id": 123,
    "property_id": 456,
    "estimated_value": 525000.00,
    "confidence_level": 85,
    "valuation_type": "neural_network",
    "created_at": "2026-02-16T04:22:00.000Z"
  },
  "message": "Valuation generated successfully"
}
```

### Detailed Report Response
```json
{
  "success": true,
  "report": {
    "property": {
      "id": 456,
      "title": "Modern 3-Bed Detached Home",
      "location": "London, UK",
      "bedrooms": 3,
      "bathrooms": 2,
      "area_sqft": 1500,
      "year_built": 2010
    },
    "valuation": {
      "estimated_value": 525000.00,
      "confidence_level": 85,
      "price_range": {
        "min": 502875.00,
        "max": 547125.00
      },
      "feature_importance": {
        "area_sqft": 35.2,
        "is_detached": 28.5,
        "bedrooms": 18.3,
        "bathrooms": 12.1,
        "age": 5.9
      },
      "market_trend": "rising"
    },
    "comparables": [...],
    "model_version": "1.0.0"
  }
}
```

## Support

For issues or questions:
1. Check the test suite for usage examples
2. Review the service class documentation
3. Consult the API endpoint documentation above
