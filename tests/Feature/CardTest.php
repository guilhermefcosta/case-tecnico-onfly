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

    public function test_can_create_card_after_login(): void
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $response = $this->postJson('/api/login', $credentials);
        $response->();
        // $response->ddHeaders();

        $cardData = [
            'card_number' => Str::random(16),
            'balance' => 2000,
        ];

        $response = $this->postJson("/api/user/". $user->id ."/card", $cardData);
        $response->assertStatus(401);
    }
    
}
