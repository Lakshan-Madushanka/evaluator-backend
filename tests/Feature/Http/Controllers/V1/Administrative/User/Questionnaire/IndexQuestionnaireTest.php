<?php

use App\Enums\Role;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;
use Tests\Repositories\UserRepository;

it('return 401 response non-login users ', function () {
    $user = UserRepository::getRandomUser();

    $response = getJson(route('api.v1.administrative.users.questionnaires.index'));
    $response->assertUnauthorized();
})->group('administrative/users/questionnaires/index');

it('return 404 response regular login users', function () {
    $user = UserRepository::getRandomUser();
    Sanctum::actingAs($user);

    $response = getJson(route('api.v1.administrative.users.questionnaires.index'));
    $response->assertNotFound();
})->group('administrative/users/questionnaires/index');

test('admin can obtain all user questionnaires', function () {
    $user = UserRepository::getRandomUser(Role::ADMIN);
    Sanctum::actingAs($user);

    $user = UserRepository::getRandomUser();

    $response = getJson(route('api.v1.administrative.users.questionnaires.index', ['user' => $user->hash_id]));
    $response->assertOk();
})->group('administrative/users/questionnaires/index');

test('can paginate user records', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::SUPER_ADMIN));

    $user = \App\Models\User::whereHas('questionnaires')->first();

    $query = http_build_query(['page' => ['size' => 1]]);

    $response = getJson(route('api.v1.administrative.users.questionnaires.index', ['user' => $user?->hash_id]));
    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
        ->hasAll(['links', 'meta', 'meta.current_page'])
        ->missing('data.0.attributes.password')
        ->etc());

    $response->assertJsonPath('meta.per_page', 1);
})->group('administrative/users/questionnaires/index');
