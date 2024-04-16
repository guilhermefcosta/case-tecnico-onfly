<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationService
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

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

    public function validateUserUpdate(Request $request, User $user)
    {   
        $this->verifyUserAction($request, $user);

        $validatedData = $request->validate([
            'name' => 'max:255',
            'email' => 'email|unique:users|max:255',
            // 'password' => 'required|max:255',
            'role' => 'in:1,2'
        ]);

        return $validatedData;
    }

    public function verifyUserAction(Request $request, User $user)
    {
        // se o usuario é adm ele pode ver tudo
        if ($request->user()->isAdmin()) {
            return $user;
        } 

        // o usuario comum só pode ser ver
        if ($request->user()->id == $user->id) {
            return $user;
        } else {
            return response()->json(['error' => 'Access Denied!'], 401)->throwResponse();
        }

    }


    public function validateCardCreation(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'card_number' => 'required|max:255|unique:cards',
            'balance' => 'required|gte:0',
        ]);

        $this->userService->checksUserOwnerOfCardOrAdmin($request, $user);

        return $validatedData;
    }

    public function validateCardUpdate(Request $request, Card $card)
    {

        $validatedData = $request->validate([
            'balance' => 'gte:0',
        ]);

        $this->userService->checksUserOwnerOfCardOrAdmin($request, $card->user);

        return $validatedData;
    }

    
    public function validateExpenseCreation(Request $request) 
    {
        $validatedData = $request->validate([
            'value' => 'required|gte:0'
        ]);

        return $validatedData;
    }

    public function validateExpenseUpdate(Request $request) 
    {
        $validatedData = $request->validate([
            'value' => 'gte:0'
        ]);

        return $validatedData;
    }


    public function validateUserLogin(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'email|required|exists:users,email|max:255',
            'password' => 'required|max:255'
        ]);

        return $validatedData;
    }


}