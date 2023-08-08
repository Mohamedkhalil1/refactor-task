<?php

namespace Database\Factories;

use App\Models\Cashier;
use App\Models\Loyalty;
use App\Models\Member;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_male' => $this->faker->boolean(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }

    // after creating add visits with loyalty points
    public function configure(): self
    {
        return $this->afterCreating(function (Member $member) {

            $hasVisits = rand(0, 1);

            if (! $hasVisits) {
                return;
            }

            $cashier = Cashier::inRandomOrder()->first();
            $member->visits()->saveMany(Visit::factory()->count(3)->make([
                'cashier_id' => $cashier->id,
            ]))->each(function (Visit $visit) {
                $visit->loyalty()->save(Loyalty::factory()->make());
            });
        });
    }
}
