<?php

use App\Enums\Role;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;
use Tests\Repositories\UserRepository;
use Tests\RequestFactories\CategoryRequest;

it('return 401 unauthorized response for non-login users', function () {
    $response = getJson(route('api.v1.administrative.questions.show', ['question' => 1]));
    $response->assertUnauthorized();
})->group('api/v1/administrative/question/show');

it('allows administrative users to retrieve all questions by hash id', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $questionHashId = \Tests\Repositories\QuestionRepository::getRandomQuestion()->hash_id;

    $response = getJson(route('api.v1.administrative.questions.show', ['question' => $questionHashId]));
    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json->hasAll('data.id', 'data.type', 'data.attributes')
        ->hasAll('data.attributes.images', 'data.attributes.categories', 'data.attributes.no_of_assigned_answers')
        ->where('data.id', $questionHashId)
        ->etc()
    );
})->fakeRequest(CategoryRequest::class)
    ->group('api/v1/administrative/question/show');
