<?php

use App\Http\Controllers\Api\V1\Administrative\Auth\LogInController;
use App\Http\Controllers\Api\V1\Administrative\Auth\LogOutController;
use App\Http\Controllers\Api\V1\Administrative\Auth\ShowAuthUserController;
use App\Http\Controllers\Api\V1\Administrative\User\IndexUserController;
use App\Http\Controllers\Api\V1\Administrative\User\ShowUserController;
use App\Http\Controllers\Api\V1\SuperAdmin\User\CreateUserController;
use App\Http\Controllers\Api\V1\SuperAdmin\User\DeleteUserController;
use App\Http\Controllers\Api\V1\SuperAdmin\User\MassDeleteUserController;
use App\Http\Controllers\Api\V1\SuperAdmin\User\UpdateUserController;
use App\Http\Requests\User\UserRequestValidationRules;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

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
Route::get('/test', function (Request $request) {
    $rules = UserRequestValidationRules::getRules();
    $rules['email'][] = Rule::unique('users', 'email');

    dd($rules);
});

Route::prefix('super-admin')->name('super-admin.')->group(function () {
    Route::middleware(['auth:sanctum', 'can:super-admin'])
        ->post('/users', CreateUserController::class)
        ->name('users.store');

    Route::middleware(['auth:sanctum', 'can:super-admin'])
        ->put('/users/{user}', UpdateUserController::class)
        ->name('users.update');

    Route::middleware(['auth:sanctum', 'can:super-admin'])
        ->delete('/users/{user}', DeleteUserController::class)
        ->name('users.delete');

    Route::middleware(['auth:sanctum', 'can:super-admin'])
        ->post('/users/mass-delete', MassDeleteUserController::class)
        ->name('users.mass-delete');
});

Route::prefix('administrative')->name('administrative.')->group(function () {
    Route::middleware(['guest'])
        ->post('/login', LogInController::class)
        ->name('login');

    Route::middleware('auth:sanctum')
        ->get('/user', ShowAuthUserController::class)
        ->name('user');

    Route::middleware(['auth:sanctum'])
        ->post('/logout', LogOutController::class)
        ->name('logout');

    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->get('/users', IndexUserController::class)
        ->name('users.index');

    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->get('/users/{user}', ShowUserController::class)
        ->name('users.show');
});
