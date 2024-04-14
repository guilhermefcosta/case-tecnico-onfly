<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Expense;
use App\Services\CardService;
use App\Services\ValidationService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Expense::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ValidationService $validationService, CardService $cardService)
    {
        $validData = $validationService->validateExpenseCreation($request);

        $card = Card::findOrFail($validData['card_id']);
        
        if ($cardService->checkCardBalance($card, $validData['value'])) {

            $expense = $cardService->makeTransaction($card, $validData);

            if ($expense) {
                return response()->json($expense, 201);
            } else {
                return response()->json(['error' => 'Failed to create expense.'], 500);
            }

        } else {
            return response()->json(['error' => 'The expense is greater than the balance.'], 400);
        }   


    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return  $expense;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense, ValidationService $validationService, CardService $cardService)
    {
        $validData = $validationService->validateExpenseUpdate($request);

        $card = Card::findOrFail($expense->card_id);
        
        if ($cardService->checkCardBalance($card, $validData['value'])) {

            $expense = $cardService->updateTransaction($card, $expense, $validData);

            if ($expense) {
                return response()->json($expense, 201);
            } else {
                return response()->json(['error' => 'Failed to create expense.'], 500);
            }

        } else {
            return response()->json(['error' => 'The expense is greater than the balance.'], 400);
        }   


        $expense->update($validData);

        return $expense;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json(['message' => 'expense deleted' ], 200);
    }
}
