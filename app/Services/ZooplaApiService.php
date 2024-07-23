
            // Add more fields as required by Zoopla API
        ];
    
        Log::info('Preparing Zoopla property data', [
            'property_id' => $property->id,
            'zoopla_id' => $property->zoopla_id,
            'data' => $data,
        ]);
    
        return $data;
    }
}