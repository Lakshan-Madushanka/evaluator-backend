<?php

use App\Enums\Role;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;
use Tests\Repositories\UserRepository;

it('return 401 response non-login users ', function () {
    $user = UserRepository::getRandomUser();

    $response = getJson(route('api.v1.administrative.users.questionnaires.index', ['user' => $user->hash_id]));
    $response->assertUnauthorized();
})->group('administrative/users/questionnaires/index');

it('return 404 response regular login users', function () {
    $user = UserRepository::getRandomUser();
    Sanctum::actingAs($user);

    $response = getJson(route('api.v1.administrative.users.questionnaires.index', ['user' => $user->hash_id]));
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

    $query = '?'.http_build_query(['page' => ['size' => 1]]);

    $route = route('api.v1.administrative.users.questionnaires.index', ['user' => $user?->hash_id]).$query;
    $response = getJson($route);
    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
        ->hasAll(['links', 'meta', 'meta.current_page'])
        ->missing('data.0.attributes.password')
        ->etc());

    $response->assertJsonPath('meta.per_page', 1);
})->group('administrative/users/questionnaires/index');

test('can filter records by expired status', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::SUPER_ADMIN));

    config(['json-api-paginate.max_results' => PHP_INT_MAX]);

    $user = \App\Models\User::whereHas('questionnaires')->first();

    $query = '?'.http_build_query([
        'filter' => ['expired' => false],
        'page' => ['size' => PHP_INT_MAX],
    ]);

    $route = route('api.v1.administrative.users.questionnaires.index', ['user' => $user?->hash_id]).$query;
    $response = getJson($route);

    $results = $response->decodeResponseJson()['data'];
    $expiredProperties = collect($results)->pluck('attributes.expires_at');

    $expiredProperties->each(function ($expiredAt) {
        expect(\Carbon\Carbon::parse($expiredAt)->gte(now()))->toBeTrue();
    });
    $response->assertOk();
})->group('administrative/users/questionnaires/index');
