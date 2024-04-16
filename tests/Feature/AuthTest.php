<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_normal_not_can_see_all_user_cards_and_expenses(): void
    {

        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'password' => Hash::make('secret'),
            'role' => 2   
        ];

        $user = User::create($userData);

        $userCredentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->postJson('/api/login', $userCredentials);

        $response = $this->get('/api/users');
        $response->assertStatus(401); // Access Denied

        $response = $this->get('/api/cards');
        $response->assertStatus(401);
        
        $response = $this->get('/api/expenses');
        $response->assertStatus(401);

        $retorno = $response->json();
        $this->assertEquals("Access Denied!", $retorno['error']);
    }

    public function test_user_admin_can_see_all_users_cards_and_expenses(): void
    {
        $user = User::factory()->create();
        $user->role = 1;
        $user->save();

        $userCredentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->postJson('/api/login', $userCredentials);

        $response = $this->get('/api/users');
        $response->assertStatus(200); // Podemos ver fazer a request de todos os usuario
        
        $response = $this->get('/api/cards');
        $response->assertStatus(200); 

        $response = $this->get('/api/expenses');
        $response->assertStatus(200); 
    }


}
