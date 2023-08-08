<?php

namespace Database\Factories;

use App\Models\Cashier;
use App\Models\Member;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition(): array
    {
        return [
            "member_id" => Member::inRandomOrder()->first()->id,
            "receipt" => $this->faker->numberBetween(1, 99),
            "cashier_id" => Cashier::inRandomOrder()->first()->id,
        ];
    }
}
