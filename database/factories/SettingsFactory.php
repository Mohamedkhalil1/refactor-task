<?php

namespace Database\Factories;

use App\Models\Cashier;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingsFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'cashier_id' => Cashier::inRandomOrder()->first()->id,
            'loyalty_model' => $this->faker->randomElement(['first_model', 'second_model']),
            'factor' => $this->faker->randomFloat(2, 0, 100),
            'min_points' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
