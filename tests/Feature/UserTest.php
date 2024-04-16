<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => 'guifcosta.contato@gmail.com',
            'password' => Hash::make('secret'),
            'role' => rand(1,2)
        ];

        $response = $this->postJson('/api/user', $userData);
        $response->assertStatus(201);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->postJson('/api/login', $credentials);

        $newUserData = [
            'name' => 'Guilherme Ferreira Costa',
            'email' => 'guifcosta.contato@gmail.com'
        ];

        $response = $this->putJson('/api/user/' .$user->id, $newUserData);
        $response->assertStatus(200);

        $jsonResult = $response->json();
        $this->assertEquals($jsonResult['name'], 'Guilherme Ferreira Costa');
        $this->assertEquals($jsonResult['email'], 'guifcosta.contato@gmail.com');
    }
    

    public function test_can_delete_user()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->postJson('/api/login', $credentials);


        $response = $this->delete('/api/user/' . $user->id);
        $response->assertStatus(200);
    }


    public function test_can_not_create_duplicate_users()
    {
        $user = User::factory()->create();

        $userData = [
            'name' => fake()->name(),
            'email' => $user->email,
            'password' => Hash::make('secret'),
            'role' => rand(1,2)
        ];

        $response = $this->postJson('/api/user', $userData);
        $response->assertStatus(422);
    }
}
