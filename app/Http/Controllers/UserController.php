<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function show(Request $request, User $user, ValidationService $validationService)
    {
        $validationService->verifyUserAction($request, $user); 
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, ValidationService $validationService)
    {
        $validData = $validationService->validateUserUpdate($request, $user);
        $user->update($validData);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user, ValidationService $validationService)
    {
        $validationService->verifyUserAction($request, $user); 
        $user->delete();

        return response()->json([
            'message' => 'user deleted'
        ]);
    }
}
