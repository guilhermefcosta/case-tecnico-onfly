<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use App\Services\UserService;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Card::all();
    }

    public function list(Request $request, UserService $userService, User $user)
    {
        $userService->checksUserOwnerOfCardOrAdmin($request, $user);
        
        $cartoes = $user->cards;
        
        return $cartoes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ValidationService $validationService, UserService $userService, User $user)
    {
        $validData = $validationService->validateCardCreation($request, $user, $userService);

        $card = $user->cards()->create($validData);

        return response()->json($card, 201);    
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, UserService $userService, User $user, Card $card)
    {

        $userService->checksUserOwnerOfCardOrAdmin($request, $user);
        
        return $card;
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, ValidationService $validationService, User $user, Card $card)
    {
        // var_dump($card->id);die;
        $validData = $validationService->validateCardUpdate($request, $card);

        $card->update($validData);

        return $card;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, UserService $userService, User $user, Card $card)
    {
        $userService->checksUserOwnerOfCardOrAdmin($request, $user);

        $card->delete();

        return response()->json([
            'message' => 'Card deleted.'
        ]);
    }
}
