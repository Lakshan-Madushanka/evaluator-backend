<?php

use App\Http\Controllers\Api\V1\SuperAdmin\User\CreateUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//\Illuminate\Support\Facades\Auth::loginUsingId(2);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('super-admin')->name('super-admin.')->group(function () {
    Route::middleware(['auth:sanctum', 'can:super-admin'])
        ->post('/users', CreateUserController::class)
        ->name('users.store');
});

Route::prefix('administrative')->name('administrative.')->group(function () {
    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->get('/users', \App\Http\Controllers\Api\V1\Administrative\IndexUserController::class)
        ->name('users.index');

    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->get('/users/{user}', \App\Http\Controllers\Api\V1\Administrative\ShowUserController::class)
        ->name('users.show');
});
