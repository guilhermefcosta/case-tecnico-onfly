<?php

namespace App\Http\Controllers;

use App\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function login(Request $request, ValidationService $validationService)
   {

        $credentials = $validationService->validateUserLogin($request);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user],200);
        }

        return response()->json(['Incorrect email or password'], 401);
   }

   public function logout(Request $request)
   {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Success on logout'], 200);
   }
}
