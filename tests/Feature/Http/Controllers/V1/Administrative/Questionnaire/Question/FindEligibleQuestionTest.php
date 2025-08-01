<?php

use App\Enums\Role;
use App\Models\Category;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;
use Tests\Repositories\QuestionRepository;
use Tests\Repositories\UserRepository;

beforeEach(function () {
    $this->questionnaire = Questionnaire::whereHas('questions')->first();

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

    $question = Question::factory()->create();

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

    $category = Category::factory()->create();

    $question = Question::factory()->create();
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

it('allows administrative users to retrieve eligible question of a questionnaires', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $question = Question::query()->eligible($this->questionnaire)->first();

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

it('return eligible false response for a multiple answer type question when questionnaire is
single answer type', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $questionnaire = Questionnaire::query()
        ->where('single_answers_type', true)
        ->whereHas('questions')
        ->whereHas('categories')
        ->first();

    $questionnaireCategoriesIds = $questionnaire?->categories()->pluck('categories.id');

    $question = Question::query()
        ->completed()
        ->first();

    $question?->categories()->sync($questionnaireCategoriesIds);

    $question->is_answers_type_single = false;
    $question?->save();

    $response = getJson(route(
        'api.v1.administrative.questionnaires.questions.findEligibleQuestion',
        [
            'questionnaire' => $questionnaire?->hash_id,
            'questionId' => $question?->pretty_id,
        ]
    ));

    $response->assertJsonPath('eligible', false);
})->group('api/v1/administrative/questionnaire/question/findEligible');
