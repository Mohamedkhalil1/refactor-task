<?php

namespace Database\Factories;

use App\Models\Loyalty;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoyaltyFactory extends Factory
{
    protected $model = Loyalty::class;

    public function definition(): array
    {
        return [
            "points" => $this->faker->numberBetween(0, 100),
            "visit_id" => Visit::inRandomOrder()->first()->id,
        ];
    }
}
