    ];

    $this->validatePropertyData($data);

    return $data;
}

protected function validatePropertyData(array $data)
{
    $validator = Validator::make($data, [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'propertyType' => 'required|string',
        'price' => 'required|numeric|min:0',
        'bedrooms' => 'required|integer|min:0',
        'bathrooms' => 'required|integer|min:0',
        'area' => 'required|numeric|min:0',
        'address.street' => 'required|string',
        'images' => 'required|array|min:1',
        'images.*' => 'url',
    ]);

    if ($validator->fails()) {
        throw new \App\Exceptions\Boo