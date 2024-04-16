<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
        

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition($id = null): array
    {
        return [
            'card_number' => fake()->creditCardNumber(),
            'balance' => random_int(1, 1000),
            'user_id' => $id ?: 1
        ];
    }
}
