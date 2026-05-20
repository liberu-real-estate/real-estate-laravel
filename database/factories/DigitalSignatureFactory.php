<?php

namespace Database\Factories;

use App\Models\DigitalSignature;
use App\Models\User;
use App\Models\Document;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class DigitalSignatureFactory extends Factory
{
    protected $model = DigitalSignature::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'document_id' => Document::factory(),
            'team_id' => Team::factory(),
        ];
    }
}