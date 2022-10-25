<?php

use App\Http\Controllers\Api\V1\SuperAdmin\CreateUserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function (Request $request) {
    return 'right';
});

Route::prefix('super-admin')->group(function () {
    \Illuminate\Support\Facades\Auth::loginUsingId(2);
    Route::get('/users', CreateUserController::class)->name('users.create');
});
