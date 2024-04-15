<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{

    public function checksUserOwnerOfCardOrAdmin(Request $request, User $user)
    {
        if (!$request->user()->isAdmin() && $user->id != $request->user()->id) { 
            return response()->json(["error" => "You do not have access to manipulate a card that is not yours."], 401)->throwResponse();
        }
    }



}