<?php

namespace App\Services;

use Illuminate\Http\Request;

class ValidationService
{
    public function validateUserCretion(Request $request)
    {   
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'email|required|unique:users|max:255',
            'password' => 'required|max:255',
            'role' => 'required|in:1,2'
        ]);

        return $validatedData;
    }

    public function validateUserUpdate(Request $request)
    {   
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'email|required|unique:users|max:255',
            // 'password' => 'required|max:255',
            'role' => 'required|in:1,2'
        ]);

        return $validatedData;
    }


    public function validateCardCreation(Request $request)
    {
        $validatedData = $request->validate([
            'card_number' => 'required|max:255|unique:cards',
            'balance' => 'required|gte:0',
            'user_id' => 'required|exists:users,id'
        ]);

        return $validatedData;
    }

    public function validateCardUpdate(Request $request)
    {
        $validatedData = $request->validate([
            // 'card_number' => 'required|max:255|unique:cards',
            'balance' => 'required|gte:0',
            // 'user_id' => 'required|exists:users,id'
        ]);

        return $validatedData;
    }


    public function validateExpenseCreation(Request $request) 
    {
        $validatedData = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'value' => 'required|gte:0'
        ]);

        return $validatedData;
    }

    public function validateExpenseUpdate(Request $request) 
    {
        $validatedData = $request->validate([
            // 'card_id' => 'required|exists:cards,id',
            'value' => 'required|gte:0'
        ]);

        return $validatedData;
    }

}