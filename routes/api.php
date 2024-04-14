<?php

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


Route::get('/users', [UserController::class, 'index']);
Route::get('/user/{user}', [UserController::class, 'show']); // o usuario adm pode ver todos os usuários, os comuns só podem se ver
Route::post('/user', [UserController::class, 'store']);
Route::put('/user/{user}', [UserController::class, 'update']);
Route::delete('/user/{user}', [UserController::class, 'destroy']); 



Route::get('/cards', [CardController::class, 'index']);
Route::get('/card/{card}', [CardController::class, 'show']);
Route::post('/card', [CardController::class, 'store']);
Route::put('/card/{card}', [CardController::class, 'update']);
Route::delete('/card/{card}', [CardController::class, 'destroy']);


Route::get('/expenses', [ExpenseController::class, 'index']);
Route::get('/expense/{expense}', [ExpenseController::class, 'show']);
Route::post('/expense', [ExpenseController::class, 'store']);
Route::put('/expense/{expense}', [ExpenseController::class, 'update']);
Route::delete('/expense/{expense}', [ExpenseController::class, 'destroy']);




