<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;


class CardTest extends TestCase
{

    use RefreshDatabase;


    public function test_can_not_create_card_without_login(): void
    {
        $user = User::factory()->create();

        $cardData = [
            'card_number' => Str::random(16),
            'balance' => 2000,
        ];

        $response = $this->postJson("/api/user/". $user->id ."/card", $cardData);
        $response->assertStatus(401);
    }

    public function test_can_create_card(): void
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $response = $this->postJson('/api/login', $credentials);
        // $response->ddHeaders();

        $cardData = [
            'card_number' => Str::random(16),
            'balance' => 2000,
        ];

        $response = $this->postJson("/api/user/". $user->id ."/card", $cardData);
        
        $this->assertGreaterThan(0, Card::all()->count()); 
        $response->assertStatus(201);
    }

    public function test_can_update_card()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->postJson('/api/login', $credentials);

        $card = $user->cards()->create([
            'card_number' => fake()->creditCardNumber(),
            'balance' => 500
        ]);

        $newCardData = [
            // 'card_number' => '1111111111111111', o número do cartao nao é alterado (bom senso prático)
            'balance' => 1000
        ];

        $response = $this->putJson('/api/user/'. $user->id .'/card/'. $card->id, $newCardData);
        $response->assertStatus(200);
        $jsonResult = $response->json();
        $this->assertEquals($jsonResult['balance'], 1000);
    }

    public function test_can_delete_card()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->postJson('/api/login', $credentials);

        $card = $user->cards()->create([
            'card_number' => fake()->creditCardNumber(),
            'balance' => 500
        ]);

        $response = $this->delete('/api/user/'.$user->id.'/card/'.$card->id);
        $response->assertStatus(200);
    }
    

    // CREATE A CARD WITH THE SAME card number
    public function test_not_same_card_number()
    {

        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->postJson('/api/login', $credentials);

        $card = $user->cards()->create([
            'card_number' => fake()->creditCardNumber(),
            'balance' => 500
        ]);

        $newCardData = [
            'card_number' => $card->card_number,
            'balance' => 100,
            'user_id' => $user->id 
        ];

        $response = $this->postJson('/api/user/'.$user->id.'/card', $newCardData);
        $response->assertStatus(422);

        $jsonResult = $response->json();
        $this->assertEquals("The card number has already been taken.", $jsonResult['message']);        
    }
    
}
