<?php

namespace Database\Factories;

use App\Models\DocumentTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTemplateFactory extends Factory
{
    protected $model = DocumentTemplate::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'file_path' => $this->faker->filePath(),
            'description' => $this->faker->sentence(),
        ];
    }
}