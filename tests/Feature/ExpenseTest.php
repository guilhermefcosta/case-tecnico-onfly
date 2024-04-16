<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;


class ExpenseTest extends TestCase
{
    
    use RefreshDatabase;

    
    public function test_create_expense(): void
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->post('/api/login', $credentials);

        $card = $user->cards()->create([
            'card_number' => fake()->creditCardNumber(),
            'balance' => 500
        ]);

        $expenseData = [
            'value' => 200
        ];

        $response = $this->postJson('/api/card/'.$card->id.'/expense', $expenseData);
        $response->assertStatus(201);
    }


    public function test_can_not_create_expense_grather_then_balance()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->post('/api/login', $credentials);

        $card = $user->cards()->create([
            'card_number' => fake()->creditCardNumber(),
            'balance' => 100
        ]);

        $expenseData = [
            'value' => 200
        ];

        $response = $this->post('/api/card/'.$card->id.'/expense', $expenseData);
        $response->assertStatus(400);
        $jsonResult = $response->json();
        $this->assertEquals($jsonResult['error'], "The expense is greater than the balance.");
    }

    public function test_update_expense_lower_and_grather_then_balance()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->post('/api/login', $credentials);

        $card = $user->cards()->create([
            'card_number' => fake()->creditCardNumber(),
            'balance' => 500
        ]);

        $response1 = $this->postJson('/api/card/'.$card->id.'/expense', ['value' => 200]);
        $expenseId = $response1->json()['id'];

        // atualiza seus atributos
        $card->refresh(); 

        // saldo do cartao
        $this->assertEquals(300, $card->balance);

        $newDataExpense = [
            'value' => 300
        ];

        // atualiza a despesa
        $response = $this->putJson('/api/card/'.$card->id.'/expense/'.$expenseId, $newDataExpense);
        $response->assertStatus(200);

        $card->refresh();
        $this->assertEquals(200, $card->balance);

        $newDataExpense = [
            'value' => 1000
        ];

        // atualiza a despesa novamente para um valor a cima do saldo do cartao
        $response = $this->putJson('/api/card/'.$card->id.'/expense/'.$expenseId, $newDataExpense);
        $response->assertStatus(400);
    }

    public function test_can_delete_expense()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $this->post('/api/login', $credentials);

        $card = $user->cards()->create([
            'card_number' => fake()->creditCardNumber(),
            'balance' => 500
        ]);

        $response1 = $this->postJson('/api/card/'.$card->id.'/expense', ['value' => 200]);
        $expenseId = $response1->json()['id'];
        
        // atualiza seus atributos
        $card->refresh(); 

        // saldo do cartao
        $this->assertEquals(300, $card->balance);

        // atualiza a despesa
        $response = $this->delete('/api/card/'.$card->id.'/expense/'.$expenseId);
        
        // atualiza seus atributos
        $card->refresh(); 

        // saldo do cartao
        $this->assertEquals(500, $card->balance); 
    }
}
