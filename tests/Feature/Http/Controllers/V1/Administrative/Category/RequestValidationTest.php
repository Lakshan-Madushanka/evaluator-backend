<?php

use App\Enums\Role;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;
use Tests\Repositories\UserRepository;
use Tests\RequestFactories\CategoryRequest;

it('required name', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $response = postJson(route('api.v1.administrative.categories.store'));
    $response->assertStatus(422);
    $response->assertInvalid(['name']);
})->group('api/v1/administrative/category/validation');

it('required unique name', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $name = \Illuminate\Support\Str::random();

    CategoryRequest::new(['name' => $name])->fake();
    $response = postJson(route('api.v1.administrative.categories.store'));
    $response->assertCreated();

    CategoryRequest::new(['name' => $name])->fake();
    $response = postJson(route('api.v1.administrative.categories.store'));
    $response->assertStatus(422);
    $response->assertInvalid(['name']);
})->group('api/v1/administrative/category/validation');
