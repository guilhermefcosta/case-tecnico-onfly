<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Card;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $userAdmin = User::factory()->create(['role' => 1]);
        $user = User::factory()->create(['role' => 2]);

        $card1 = Card::create([
            'card_number' => fake()->creditCardNumber(),
            'balance' => fake()->randomFloat(2,500,1000),
            'user_id' => $user->id
        ]);

        $card2 = Card::create([
            'card_number' => fake()->creditCardNumber(),
            'balance' => fake()->randomFloat(2,500,1000),
            'user_id' => $user->id
        ]);
        
    
        Expense::create([
            'value' => 100,
            'card_id' => $card1->id
        ]);
    
        Expense::create([
            'value' => 50,
            'card_id' => $card1->id
        ]);

        Expense::create([
            'value' => 20,
            'card_id' => $card2->id
        ]);


        
    }
}
