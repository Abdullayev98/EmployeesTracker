<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('transactions/user-transactions', [TransactionController::class, 'userTransactions']);
Route::get('transactions/user-last-transactions', [TransactionController::class, 'userLastTransactions']);

Route::resource('users',UsersController::class,);
Route::resource('positions',PositionController::class);
Route::resource('transactions',TransactionController::class);

Route::post('login', [AuthController::class, 'login']);

//Route::get('transactions', [TransactionController::class, 'index']);
//Route::get('transactions/create', [TransactionController::class, 'create']);
//Route::post('transactions', [TransactionController::class, 'store']);
//Route::get('transactions/{transaction}/edit', [TransactionController::class, 'edit']);
//Route::put('transactions/{transaction}', [TransactionController::class, 'update']);
//Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy']);

//Route::get('transactions/user-transactions', [TransactionController::class, 'userTransactions']);
//Route::get('test', [TransactionController::class, 'test']);



