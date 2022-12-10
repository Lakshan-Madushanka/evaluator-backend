<?php

use App\Enums\Role;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;
use Tests\Repositories\UserRepository;
use Tests\RequestFactories\CategoryRequest;

beforeEach(function () {
    $this->questionnaire = \App\Models\Questionnaire::whereHas('questions')->first();

    $this->route = route(
        'api.v1.administrative.questionnaires.questions.index',
        ['questionnaire' => $this->questionnaire->hash_id]
    );
});

it('return 401 unauthorized response for non-login users', function () {
    $response = getJson($this->route);
    $response->assertUnauthorized();
})->group('api/v1/administrative/questionnaire/question/index');

it('allows administrative users to retrieve all questions of a questionnaires', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $questionsCount = $this->questionnaire->questions->count();

    $response = getJson($this->route);
    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json->has('data', $questionsCount)
        ->has('data.0', fn (AssertableJson $json) => $json->where('type', 'questions')
            ->etc()
        )
        ->etc()
    );
})->fakeRequest(CategoryRequest::class)
    ->group('api/v1/administrative/questionnaire/question/index');
