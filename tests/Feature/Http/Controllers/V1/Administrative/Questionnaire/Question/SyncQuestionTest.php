<?php

use App\Enums\Difficulty;
use App\Enums\Role;
use App\Models\Question;
use App\Models\Questionnaire;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;
use Tests\Repositories\QuestionRepository;
use Tests\Repositories\UserRepository;

beforeEach(function () {
    $this->questionnaire = Questionnaire::factory()->create([
        'no_of_questions' => 6,
        'no_of_easy_questions' => 2,
        'no_of_medium_questions' => 2,
        'no_of_hard_questions' => 2,
    ]);

    $category = \App\Models\Category::query()->first();

    $this->questionnaire->categories()->attach($category->id);

    $answersIds = \App\Models\Answer::query()->limit(2)->pluck('id');

    $easyQuestions = Question::factory()->count(4)->create([
        'difficulty' => Difficulty::EASY,
        'no_of_answers' => 2,
    ])
        ->each(fn (Question $question) => $question->answers()->sync($answersIds));

    $mediumQuestions = Question::factory()->count(4)->create([
        'difficulty' => Difficulty::MEDIUM,
        'no_of_answers' => 2,
    ])
        ->each(fn (Question $question) => $question->answers()->sync($answersIds));

    $hardQuestions = Question::factory()->count(4)->create([
        'difficulty' => Difficulty::HARD,
        'no_of_answers' => 2,
    ])
        ->each(fn (Question $question) => $question->answers()->sync($answersIds));

    $easyQuestionsIds = $easyQuestions->pluck('id');
    $mediumQuestionsIds = $mediumQuestions->pluck('id');
    $hardQuestionsIds = $hardQuestions->pluck('id');

    $ids = $easyQuestionsIds->merge([$mediumQuestionsIds, $hardQuestionsIds])->flatten()->all();

    $category->questions()->sync($ids);
});

it('return 401 unauthorized response for non-login users', function () {
    $response = postJson(
        route('api.v1.administrative.questionnaires.questions.sync', ['questionnaire' => 'abcd']),
        ['question' => []]
    );
    $response->assertUnauthorized();
})->group('api/v1/administrative/questionnaire/question/sync');

it('throws validation exception when try to sync no of question greater than allowed no of question', function () {
    Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

    $payload = QuestionRepository::pluckCompletedQuestionsHashIds($this->questionnaire->no_of_questions + 1,
        $this->questionnaire);

    $response = postJson(route(
        'api.v1.administrative.questionnaires.questions.sync',
        ['questionnaire' => $this->questionnaire->hash_id]
    ),
        ['questions' => $payload->all()]
    );

    $response->assertUnprocessable();
    $response->assertInvalid(['questions']);
})->group('api/v1/administrative/questionnaire/question/sync');

it('throws validation exception when try to sync no of easy question greater than allowed no of easy question',
    function () {
        Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

        $payload = QuestionRepository::pluckCompletedQuestionsHashIdsByDifficulty(
            $this->questionnaire->no_of_easy_questions + 1,
            Difficulty::EASY,
            $this->questionnaire
        );

        $response = postJson(
            route('api.v1.administrative.questionnaires.questions.sync',
                ['questionnaire' => $this->questionnaire->hash_id]),
            ['questions' => $payload->all()]
        );

        $response->assertUnprocessable();
        $response->assertInvalid(['questions']);
    })->group('api/v1/administrative/questionnaire/question/sync');

it('throws validation exception when try to sync no of medium question greater than allowed no of medium question',
    function () {
        Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

        $payload = QuestionRepository::pluckCompletedQuestionsHashIdsByDifficulty(
            $this->questionnaire->no_of_medium_questions + 1,
            Difficulty::MEDIUM,
            $this->questionnaire
        );

        $response = postJson(
            route(
                'api.v1.administrative.questionnaires.questions.sync',
                ['questionnaire' => $this->questionnaire->hash_id]
            ),
            ['questions' => $payload->all()]
        );

        $response->assertUnprocessable();
        $response->assertInvalid(['questions']);
    })->group('api/v1/administrative/questionnaire/question/sync');

it('throws validation exception when try to sync no of hard question greater than allowed no of hard question',
    function () {
        Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

        $payload = QuestionRepository::pluckCompletedQuestionsHashIdsByDifficulty(
            $this->questionnaire->no_of_hard_questions + 1,
            Difficulty::HARD,
            $this->questionnaire
        );

        $response = postJson(
            route('api.v1.administrative.questionnaires.questions.sync',
                ['questionnaire' => $this->questionnaire->hash_id]),
            ['questions' => $payload->all()]
        );

        $response->assertUnprocessable();
        $response->assertInvalid(['questions']);
    })->group('api/v1/administrative/questionnaire/question/sync');

it('allows administrative users to sync questions',
    function () {
        Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

        $easyQuestions = QuestionRepository::pluckCompletedQuestionsHashIdsByDifficulty(
            $this->questionnaire->no_of_easy_questions,
            Difficulty::EASY,
            $this->questionnaire
        );
        $mediumQuestions = QuestionRepository::pluckCompletedQuestionsHashIdsByDifficulty(
            $this->questionnaire->no_of_medium_questions,
            Difficulty::MEDIUM,
            $this->questionnaire
        );
        $hardQuestions = QuestionRepository::pluckCompletedQuestionsHashIdsByDifficulty(
            $this->questionnaire->no_of_hard_questions,
            Difficulty::HARD,
            $this->questionnaire
        );

        $payload = $easyQuestions->concat([$mediumQuestions, $hardQuestions])->flatten();

        $response = postJson(
            route('api.v1.administrative.questionnaires.questions.sync',
                ['questionnaire' => $this->questionnaire->hash_id]),
            ['questions' => $payload->all()]
        );

        $response->assertOk();

        $questionsIds = $this->questionnaire->questions->pluck('id')->transform(fn ($id
        ) => \Vinkla\Hashids\Facades\Hashids::encode($id));

        expect($payload->diff($questionsIds))->toEqual(collect([]));
    })->group('api/v1/administrative/questionnaire/question/sync');

it('allows administrative users to remove all questions of a questionnaire',
    function () {
        Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

        $response = postJson(
            route('api.v1.administrative.questionnaires.questions.sync',
                ['questionnaire' => $this->questionnaire->hash_id]),
            ['questions' => []]
        );

        $response->assertOk();

        $questionsIds = $this->questionnaire->questions->pluck('id');

        expect($questionsIds->count())->toEqual(0);
    })->group('api/v1/administrative/questionnaire/question/sync');

it('cannot attach question which belongs to different category than questionnaire',
    function () {
        Sanctum::actingAs(UserRepository::getRandomUser(Role::ADMIN));

        $questionnaireCategoryIds = $this->questionnaire->categories()->pluck('categories.id');

        $question = Question::factory()
            ->create();

        $response = postJson(
            route('api.v1.administrative.questionnaires.questions.sync',
                ['questionnaire' => $this->questionnaire->hash_id]),
            ['questions' => [\Vinkla\Hashids\Facades\Hashids::encode($question->id)]]
        );

        $response->assertOk();

        $questionsIds = $this->questionnaire->questions->pluck('id');

        expect($questionsIds->count())->toEqual(0);
    })->group('api/v1/administrative/questionnaire/question/sync');
