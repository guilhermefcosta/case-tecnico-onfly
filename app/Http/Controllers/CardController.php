<?php

namespace App\Http\Controllers;

use App\Models\Card;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ValidationService $validationService)
    {
        $validData = $validationService->validateCardCreation($request);

        $card = Card::create($validData);

        return response()->json($card, 201);    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        return $card;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card, ValidationService $validationService)
    {
        $validData = $validationService->validateCardUpdate($request);

        $card->update($validData);

        return $card;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $card->delete();

        return response()->json([
            'message' => 'card deleted'
        ]);
    }
}
