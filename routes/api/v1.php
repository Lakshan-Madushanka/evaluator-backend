<?php

use App\Http\Controllers\Api\V1\Administrative\Answer\CheckAnswerExistsController;
use App\Http\Controllers\Api\V1\Administrative\Answer\DeleteAnswerController;
use App\Http\Controllers\Api\V1\Administrative\Answer\IndexAnswerController;
use App\Http\Controllers\Api\V1\Administrative\Answer\MassDeleteAnswerController;
use App\Http\Controllers\Api\V1\Administrative\Answer\ShowAnswerController;
use App\Http\Controllers\Api\V1\Administrative\Answer\StoreAnswerController;
use App\Http\Controllers\Api\V1\Administrative\Answer\UpdateAnswerController;
use App\Http\Controllers\Api\V1\Administrative\Auth\LogInController;
use App\Http\Controllers\Api\V1\Administrative\Auth\LogOutController;
use App\Http\Controllers\Api\V1\Administrative\Auth\ShowAuthUserController;
use App\Http\Controllers\Api\V1\Administrative\Category\DeleteCategoryController;
use App\Http\Controllers\Api\V1\Administrative\Category\IndexCategoryController;
use App\Http\Controllers\Api\V1\Administrative\Category\ShowCategoryController;
use App\Http\Controllers\Api\V1\Administrative\Category\StoreCategoryController;
use App\Http\Controllers\Api\V1\Administrative\Category\UpdateCategoryController;
use App\Http\Controllers\Api\V1\Administrative\Profile\UpdateProfileController;
use App\Http\Controllers\Api\V1\Administrative\Question\Answer\AsyncAnswerController;
use App\Http\Controllers\Api\V1\Administrative\Question\DeleteQuestionController;
use App\Http\Controllers\Api\V1\Administrative\Question\IndexQuestionController;
use App\Http\Controllers\Api\V1\Administrative\Question\MassDeleteQuestionController;
use App\Http\Controllers\Api\V1\Administrative\Question\ShowQuestionController;
use App\Http\Controllers\Api\V1\Administrative\Question\StoreQuestionController;
use App\Http\Controllers\Api\V1\Administrative\Question\UpdateQuestionController;
use App\Http\Controllers\Api\V1\Administrative\Questionnaire\DeleteQuestionnaireController;
use App\Http\Controllers\Api\V1\Administrative\Questionnaire\IndexQuestionnaireController;
use App\Http\Controllers\Api\V1\Administrative\Questionnaire\MassDeleteQuestionnaireController;
use App\Http\Controllers\Api\V1\Administrative\Questionnaire\ShowQuestionnaireController;
use App\Http\Controllers\Api\V1\Administrative\Questionnaire\StoreQuestionnaireController;
use App\Http\Controllers\Api\V1\Administrative\Questionnaire\UpdateQuestionnaireController;
use App\Http\Controllers\Api\V1\Administrative\User\IndexUserController;
use App\Http\Controllers\Api\V1\Administrative\User\ShowUserController;
use App\Http\Controllers\Api\V1\FileUploadController;
use App\Http\Controllers\Api\V1\SuperAdmin\User\CreateUserController;
use App\Http\Controllers\Api\V1\SuperAdmin\User\DeleteUserController;
use App\Http\Controllers\Api\V1\SuperAdmin\User\MassDeleteUserController;
use App\Http\Controllers\Api\V1\SuperAdmin\User\UpdateUserController;
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

Illuminate\Support\Facades\Auth::loginUsingId(2);
Route::get('/test', function (Request $request) {
});

Route::prefix('super-admin')->name('super-admin.')->group(function () {
    /**
     * Users
     */
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
    /*
     * Authentication
     */
    Route::middleware(['guest', 'throttle:5,1'])
        ->post('/login', LogInController::class)
        ->name('login');
    Route::middleware(['auth:sanctum'])
        ->get('/user', ShowAuthUserController::class)
        ->name('user');
    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->put('/profile/{user}', UpdateProfileController::class)
        ->name('profile');
    Route::middleware(['auth:sanctum'])
        ->post('/logout', LogOutController::class)
        ->name('logout');

    /*
     *Users
     */
    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->get('/users', IndexUserController::class)
        ->name('users.index');
    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->get('/users/{user}', ShowUserController::class)
        ->name('users.show');

    /*
     *Categories
     */
    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->name('categories.')
        ->prefix('categories')
        ->group(function () {
            Route::get('/', IndexCategoryController::class)->name('index');
            Route::get('/{category}', ShowCategoryController::class)->name('show');
            Route::post('/', StoreCategoryController::class)->name('store');
            Route::put('/{category}', UpdateCategoryController::class)->name('update');
            Route::middleware(['can:super-admin'])->delete('/{category}',
                DeleteCategoryController::class)->name('delete');
        });

    /*
    *Questions
    */

    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->name('questions.')
        ->prefix('questions')
        ->group(function () {
            Route::get('/', IndexQuestionController::class)->name('index');
            Route::get('/{question}', ShowQuestionController::class)->name('show');
            Route::middleware(['xss-protect'])->post('/', StoreQuestionController::class)->name('store');
            Route::middleware(['xss-protect'])->put('/{question}', UpdateQuestionController::class)->name('update');
            Route::delete('/{question}', DeleteQuestionController::class)->name('delete');
            Route::post('/mass-delete', MassDeleteQuestionController::class)->name('mass-delete');

            // Answers
            Route::name('answers.index')->get('{question}/answers',
                \App\Http\Controllers\Api\V1\Administrative\Question\Answer\IndexAnswerController::class);
            Route::name('answers.async')->post('{question}/answers', AsyncAnswerController::class);
        });

    /**
     * Answers
     */
    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->name('answers.')
        ->prefix('answers')
        ->group(function () {
            Route::get('/{id}/exists', CheckAnswerExistsController::class)->name('checkExists');
            Route::get('/', IndexAnswerController::class)->name('index');
            Route::get('/{answer}', ShowAnswerController::class)->name('show');
            Route::middleware(['xss-protect'])->post('/', StoreAnswerController::class)->name('store');
            Route::middleware(['xss-protect'])->put('/{answer}', UpdateAnswerController::class)->name('update');
            Route::delete('/{answer}', DeleteAnswerController::class)->name('delete');
            Route::post('/mass-delete', MassDeleteAnswerController::class)->name('mass-delete');
        });

    /**
     * Questionnaire
     */
    Route::middleware(['auth:sanctum', 'can:administrative'])
        ->name('questionnaires.')
        ->prefix('questionnaires')
        ->group(function () {
            Route::get('/', IndexQuestionnaireController::class)->name('index');
            Route::get('/{questionnaire}', ShowQuestionnaireController::class)->name('show');
            Route::middleware(['xss-protect'])->post('/', StoreQuestionnaireController::class)->name('store');
            Route::middleware(['xss-protect'])->put('/{questionnaire}',
                UpdateQuestionnaireController::class)->name('update');
            Route::delete('/{questionnaire}', DeleteQuestionnaireController::class)->name('delete');
            Route::post('/mass-delete', MassDeleteQuestionnaireController::class)->name('mass-delete');
        });
});

/*
 * File Uploads
 */

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('uploads/{type}/{modelId}', [FileUploadController::class, 'index'])
        ->name('uploads.index');
    Route::post('uploads/{type}/{id}', [FileUploadController::class, 'store'])
        ->name('uploads.store');
    Route::post('uploads-change-order/{type}', [FileUploadController::class, 'changeOrder'])
        ->name('uploads.changeOrder');
    Route::post('uploads-mass-delete/{type}', [FileUploadController::class, 'massDelete'])
        ->name('uploads.massDelete');
});
