<?php

use App\Enums\Difficulty;
use App\Enums\Role;
use App\Models\Category;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;
use Tests\Repositories\QuestionRepository;
use Tests\Repositories\UserRepository;
use Tests\RequestFactories\CategoryRequest;
use Tests\RequestFactories\QuestionRequest;

beforeEach(function () {
    $this->route = route('api.v1.administrative.questions.index');
});

it('return 401 unauthorized response for non-login users', function () {
    $response = getJson($this->route);
    $response->assertUnauthorized();
})->group('api/v1/administrative/question/index');

it('allows administrative users to retrieve all questions', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $questionsCount = QuestionRepository::getTotalQuestionsCount();

    config(['json-api-paginate.max_results' => PHP_INT_MAX]);

    $query = '?'.http_build_query([
        'page' => ['size' => PHP_INT_MAX],
    ]);

    $response = getJson($this->route.$query);
    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json->has('data', $questionsCount)->etc());
})->fakeRequest(CategoryRequest::class)
    ->group('api/v1/administrative/question/index');

it('can sorts all questions by created at column', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $questionsCount = QuestionRepository::getTotalQuestionsCount();

    config(['json-api-paginate.max_results' => PHP_INT_MAX]);

    $query = '?'.http_build_query([
        'sort' => '-created_at',
        'page' => ['size' => PHP_INT_MAX],
    ]);

    $response = getJson($this->route.$query);
    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json->has('data', $questionsCount)->etc());

    $data = $response->decodeResponseJson()['data'];
    $data = collect($data)->pluck('attributes.created_at')->map(function ($created_at) {
        return Carbon::parse($created_at)->getTimestamp();
    });

    $sortedData = $data->sortDesc()->values();

    expect($data->all())->toBe($sortedData->all());
})->fakeRequest(CategoryRequest::class)
    ->group('api/v1/administrative/question/index');

it('can filter all questions by categories name', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    config(['json-api-paginate.max_results' => PHP_INT_MAX]);

    $questionName = Category::whereHas('questions')->first()->name;

    $query = '?'.http_build_query([
        'filter' => ['categories.name' => $questionName],
        'page' => ['size' => PHP_INT_MAX],
    ]);

    $response = getJson($this->route.$query);
    $response->assertOk();

    $data = $response->decodeResponseJson()['data'];

    $categoriesNames = collect($data)
        ->pluck('attributes.categories')
        ->collapse()
        ->pluck('attributes.name')
        ->filter();

    $categoriesNames->each(function (string $name) use ($questionName) {
        expect($name)->toBe($questionName);
    });
})->fakeRequest(CategoryRequest::class)
    ->group('api/v1/administrative/question/index');

it('can filter all questions by difficulty', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    config(['json-api-paginate.max_results' => PHP_INT_MAX]);

    $filteredDifficulty = Difficulty::EASY->name;

    $query = '?'.http_build_query([
        'filter' => ['hardness' => $filteredDifficulty],
        'page' => ['size' => PHP_INT_MAX],
    ]);

    $response = getJson($this->route.$query);
    $response->assertOk();

    $data = $response->decodeResponseJson()['data'];

    collect($data)->pluck('attributes.hardness')
        ->filter()
        ->each(fn (string $difficulty) => expect($filteredDifficulty)->toBe($difficulty));
})->fakeRequest(CategoryRequest::class)
    ->group('api/v1/administrative/question/index');

it('can filter all questions by its content', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $text = \Illuminate\Support\Str::random().'test'.\Illuminate\Support\Str::random();

    Question::create(QuestionRequest::new(['text' => $text])->create());

    config(['json-api-paginate.max_results' => PHP_INT_MAX]);

    $filteredDifficulty = Difficulty::EASY->name;

    $query = '?'.http_build_query([
        'filter' => ['content' => 'test'],
        'page' => ['size' => PHP_INT_MAX],
    ]);

    $response = getJson($this->route.$query);
    $response->assertOk();

    $data = $response->decodeResponseJson()['data'];

    collect($data)->pluck('attributes.content')
        ->each(fn (string $content) => expect(str_contains($content, 'test'))->toBeTrue());
})->fakeRequest(CategoryRequest::class)
    ->group('api/v1/administrative/question/index');
