<?php

namespace App\Services;

use App\Models\Card;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class CardService {

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

            $expense = Expense::create($expenseData);
            $card->balance -= $expenseData['value'];
            $card->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        
        return $expense;
            
    }

    public function updateTransaction(Card $card, Expense $expense, array $expenseData)
    {

        try {
            DB::beginTransaction();

            $expense->update($expenseData);
            $card->balance -= $expenseData['value'];
            $card->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        
        return $expense;
            
    }

}