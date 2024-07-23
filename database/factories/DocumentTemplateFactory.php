<?php

namespace Database\Factories;

use App\Models\DocumentTemplate;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTemplateFactory extends Factory
{
    protected $model = DocumentTemplate::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'file_path' => $this->faker->filePath(),
            'description' => $this->faker->sentence,
            'team_id' => Team::factory(),
        ];
    }
}