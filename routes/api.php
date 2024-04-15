<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->scopeBindings()->group(function() {
    Route::get('/users', [UserController::class, 'index'])->middleware('admin'); //  busca todos os registros de usuarios
    Route::get('/user/{user}', [UserController::class, 'show']); // o usuario adm pode ver todos os usuários, os comuns só podem se ver
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::delete('/user/{user}', [UserController::class, 'destroy']);

    Route::get('/cards', [CardController::class, 'index'])->middleware('admin'); // busca todos os registros de cartoes
    Route::get('/user/{user}/cards/', [CardController::class, 'list']); // lista os cartoes do usuario
    Route::get('/user/{user}/card/{card}', [CardController::class, 'show']);
    Route::post('/user/{user}/card', [CardController::class, 'store']);
    Route::put('/user/{user}/card/{card}', [CardController::class, 'update']);
    Route::delete('/user/{user}/card/{card}', [CardController::class, 'destroy']);
    
    Route::get('/expenses', [ExpenseController::class, 'index'])->middleware('admin'); // busca todos os registros de despesas
    Route::get('/card/{card}/expenses', [ExpenseController::class, 'list']);
    Route::get('/card/{card}/expense/{expense}', [ExpenseController::class, 'show']);
    Route::post('/card/{card}/expense', [ExpenseController::class, 'store']);
    Route::put('/card/{card}/expense/{expense}', [ExpenseController::class, 'update']);
    Route::delete('/card/{card}/expense/{expense}', [ExpenseController::class, 'destroy']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Login
Route::post('/login', [AuthController::class, 'login']); 
// Criacao de usuario
Route::post('/user', [UserController::class, 'store']); 
