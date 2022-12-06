<?php

use App\Enums\Role;
use App\Helpers;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;
use Tests\Repositories\CategoryRepository;
use Tests\Repositories\QuestionRepository;
use Tests\Repositories\UserRepository;
use Tests\RequestFactories\QuestionRequest;

it('return 401 unauthorized response for non-login users', function () {
    $response = postJson(
        route('api.v1.administrative.questions.answers.async', ['question' => 'abcd']),
        ['ids' => []]
    );
    $response->assertUnauthorized();
})->group('api/v1/administrative/question/answer/async');

it('throws validation exception when try to sync no of answers greater than allowed no of answers', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $question = QuestionRepository::getRandomQuestion();

    $categoriesIds = CategoryRepository::getRandomCategories($question->no_of_answers + 1)->pluck('id')->all();
    $categoriesHashIds = Helpers::getHashIdsFromModelIds($categoriesIds);

    $response = postJson(
        route('api.v1.administrative.questions.answers.async', ['question' => $question->hash_id]),
        ['ids' => $categoriesHashIds]
    );

    $response->assertUnprocessable();
    $response->assertInvalid(['ids']);
})->fakeRequest(QuestionRequest::class)
    ->group('api/v1/administrative/question/answer/async');

it('allows administrative users to async answers to question', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $question = QuestionRepository::getRandomQuestion();

    $categoriesIds = CategoryRepository::getRandomCategories($question->no_of_answers)->pluck('id')->all();
    $categoriesHashIds = Helpers::getHashIdsFromModelIds($categoriesIds);

    $response = postJson(
        route('api.v1.administrative.questions.answers.async', ['question' => $question->hash_id]),
        ['ids' => $categoriesHashIds]
    );
    $response->assertOk();

    $newCategoriesIds = $question->answers->pluck('id')->all();

    expect($categoriesIds)->toBe($newCategoriesIds);
})->fakeRequest(QuestionRequest::class)
    ->group('api/v1/administrative/question/answer/async');
