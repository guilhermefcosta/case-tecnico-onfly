<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ValidationService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ValidationService $validationService)
    {   
        $validData = $validationService->validateUserCretion($request);

        $user = User::create($validData);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return  $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, ValidationService $validationService)
    {
        $validData = $validationService->validateUserUpdate($request);

        $user->update($validData);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'user deleted'
        ]);
    }
}
