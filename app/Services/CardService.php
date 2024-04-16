<?php

namespace App\Services;

use App\Models\Card;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardService 
{

    public function checkCardBalance (Card $card, float $value)
    {
        if ($card->balance >= $value) {
            return true;
        }

        return false;

    }

    public function makeTransaction(Card $card, array $expenseData)
    {
        $expense = null;

        try {
            DB::beginTransaction();

            $expense = $card->expenses()->create($expenseData); // cria despesa
            $card->balance -= $expenseData['value']; // atualiza o saldo
            $card->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            var_dump($e->getMessage());die;
        }
        
        return $expense;
            
    }


    public function makeCorrectionOnCardBalanceOnUpdate(Card $card, Expense $expense, array $expenseData)
    {
        try {
            DB::beginTransaction();

            if ($expense->value >= $expenseData['value']) {
                $card->balance += $expense->value - $expenseData['value'];
                $card->save();
            } else {
                $newDebit =  $expenseData['value'] - $expense->value;

                if (($card->balance - $newDebit) < 0) {
                    return response()->json(['error' => 'The expense is greater than the balance.'], 400);
                } 

                $card->balance -= $newDebit;
                $card->save();
            }

            $expense->value = $expenseData['value'];
            $expense->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update expense.'], 500)->throwResponse();
        }

        return [];
    }

    public function makeCorrectionOnCardBalanceOnDelete(Card $card, Expense $expense)
    {
        try {
            DB::beginTransaction();

            $card->balance += $expense->value;
            $card->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete expense.'], 500)->throwResponse();
        }
    }

}