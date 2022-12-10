<?php

use App\Enums\Role;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;
use Tests\Repositories\QuestionRepository;
use Tests\Repositories\UserRepository;

beforeEach(function () {
    $this->questionnaire = \App\Models\Questionnaire::whereHas('questions')->inRandomOrder()->first();

    $this->questionnaireCategoriesIds = $this->questionnaire->categories()->pluck('categories.id');
});

it('return 401 unauthorized response for non-login users', function () {
    $question = QuestionRepository::pluckCompletedQuestionsHashIds(1, $this->questionnaire);

    $response = getJson(route(
        'api.v1.administrative.questionnaires.questions.findEligibleQuestion',
        [
            'questionnaire' => $this->questionnaire->hash_id,
            'questionId' => $question[0],
        ]
    ));

    $response->assertUnauthorized();
})->group('api/v1/administrative/questionnaire/question/findEligible');

it('return eligible false response for a question doesnt have assigned required no of answers', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $question = \App\Models\Question::factory()->create();

    $response = getJson(route(
        'api.v1.administrative.questionnaires.questions.findEligibleQuestion',
        [
            'questionnaire' => $this->questionnaire->hash_id,
            'questionId' => $question->pretty_id,
        ]
    ));

    $response->assertJsonPath('eligible', false);
})->group('api/v1/administrative/questionnaire/question/findEligible');

it('return eligible false response for a question belongs to different category', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $category = \App\Models\Category::factory()->create();

    $question = \App\Models\Question::factory()->create();
    $question->categories()->attach($category->id);

    $response = getJson(route(
        'api.v1.administrative.questionnaires.questions.findEligibleQuestion',
        [
            'questionnaire' => $this->questionnaire->hash_id,
            'questionId' => $question->pretty_id,
        ]
    ));

    $response->assertJsonPath('eligible', false);
})->group('api/v1/administrative/questionnaire/question/findEligible');

it('allows administrative users to retrieve all questions of a questionnaires', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $question = \App\Models\Question::query()->eligible($this->questionnaire)->first();

    $response = getJson(route(
        'api.v1.administrative.questionnaires.questions.findEligibleQuestion',
        [
            'questionnaire' => $this->questionnaire->hash_id,
            'questionId' => $question->pretty_id,
        ]
    ));

    $response->assertJson(fn (AssertableJson $json) => $json->where('data.id', $question->hash_id)
                ->hasAll(['data.attributes.no_of_assigned_answers', 'data.attributes.images_count'])
        ->etc()
    );
})->group('api/v1/administrative/questionnaire/question/findEligible');
