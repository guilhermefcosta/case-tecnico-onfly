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

    public function updateTransaction(Card $card, Expense $expense, array $expenseData)
    {

        try {
            DB::beginTransaction();

            $expense->update($expenseData);
            $card->balance -= $expenseData['value'];
            $card->save(); // atualiza o saldo

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        
        return $expense;
            
    }

}