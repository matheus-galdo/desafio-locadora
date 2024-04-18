<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loan_date' => $this->faker->date(),
        ];
    }

    public function returned(): static
    {
        return $this->state(
            fn ($attributes) => [
                'loan_date' => $this->faker->date(),
                'return_date' => $this->faker->date(),
            ]
        );
    }
}
