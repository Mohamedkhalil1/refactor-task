<?php

namespace Database\Factories;

use App\Models\Cashier;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashierFactory extends Factory
{
    protected $model = Cashier::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }

    // every cahsuier should have settings
    public function configure()
    {
        return $this->afterCreating(function (Cashier $cashier) {
            $cashier->settings()->create(SettingsFactory::new()->make()->toArray());
        });
    }
}
