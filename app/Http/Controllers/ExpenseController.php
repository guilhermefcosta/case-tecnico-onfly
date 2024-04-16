<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Expense;
use App\Services\CardService;
use App\Services\EmailService;
use App\Services\UserService;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Expense::all();
    }


    public function list(Request $request, UserService $userService, Card $card)
    {
        $userService->checksUserOwnerOfCardOrAdmin($request, $card->user);

        return $card->expenses;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ValidationService $validationService, CardService $cardService, EmailService $emailService, UserService $userService, Card $card)
    {
        $validData = $validationService->validateExpenseCreation($request);
        $userService->checksUserOwnerOfCardOrAdmin($request, $card->user);

        if ($cardService->checkCardBalance($card, $validData['value'])) {

            $expense = $cardService->makeTransaction($card, $validData);

            if ($expense) {
                $emailService::sendEmailExpense($expense);                
                return response()->json($expense, 201);
            } else {
                return response()->json(['error' => 'Failed to create expense.'], 500);
            }

        } else {
            return response()->json(['error' => 'The expense is greater than the balance.'], 400);
        }   
    }

    public function show(Request $request, UserService $userService, Card $card, Expense $expense)
    {
        $userService->checksUserOwnerOfCardOrAdmin($request, $card->user);
        return $expense;
    }


    public function update(Request $request, ValidationService $validationService, CardService $cardService, UserService $userService, Card $card, Expense $expense )
    {
        $validData = $validationService->validateExpenseUpdate($request);
        $userService->checksUserOwnerOfCardOrAdmin($request, $card->user); // valida se o cartao da despesa é do usuario
        
        // Faz a correcao no valor do saldo do cartão para depois validar se ele tem condicoes de enferentar o valor da nova despesa
        $resp = $cardService->makeCorrectionOnCardBalanceOnUpdate($card, $expense, $validData);

        if ($resp) {
            return $resp;
        }

        $expense->update($validData);

        return $expense;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, UserService $userService, CardService $cardService, Card $card, Expense $expense)
    {
        $userService->checksUserOwnerOfCardOrAdmin($request, $card->user); // valida se o cartao da despesa é do usuario

        $cardService->makeCorrectionOnCardBalanceOnDelete($card, $expense);

        $expense->delete();

        return response()->json(['message' => 'expense deleted' ], 200);
    }
}
