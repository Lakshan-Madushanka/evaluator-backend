<?php

use App\Enums\Role;
use App\Models\Questionnaire;
use App\Models\UserQuestionnaire;
use App\Notifications\QuestionnaireAttachedToUser;
use Laravel\Sanctum\Sanctum;
use Tests\Repositories\UserRepository;

use function Pest\Laravel\postJson;

it('return 401 response non-login users ', function () {
    $user = UserRepository::getRandomUser();

    $response = postJson(route('api.v1.administrative.users.questionnaires.attach',
        ['user' => $user->hash_id, 'questionnaireId' => 'abc']));
    $response->assertUnauthorized();
})->group('administrative/users/questionnaires/index');

it('return 404 response regular login users', function () {
    $user = UserRepository::getRandomUser();
    Sanctum::actingAs($user);

    $response = postJson(route('api.v1.administrative.users.questionnaires.attach',
        ['user' => $user->hash_id, 'questionnaireId' => 'abc']));
    $response->assertNotFound();
})->group('administrative/users/questionnaires/index');

test('return eligible false for uncompleted questionnaire', function () {
    $user = UserRepository::getRandomUser(Role::ADMIN);
    Sanctum::actingAs($user);

    $user = UserRepository::getRandomUser();

    $questionnaire = Questionnaire::factory()->create();

    $response = postJson(route(
        'api.v1.administrative.users.questionnaires.attach', [
            'user' => $user->hash_id,
            'questionnaireId' => $questionnaire->hash_id,
        ])
    );

    $response->assertJsonPath('eligible', false);
})->group('administrative/users/questionnaires/attach');

test('allows attach eligible questionnaire to a user', function () {
    \Illuminate\Support\Facades\Notification::fake();

    Sanctum::actingAs(UserRepository::getRandomUser(Role::SUPER_ADMIN));

    $user = UserRepository::getRandomUser();

    $questionnaire = Questionnaire::query()
        ->withCount('questions')
        ->completed(value: true)
        ->first();

    $response = postJson(
        route('api.v1.administrative.users.questionnaires.attach', [
            'user' => $user?->hash_id,
            'questionnaireId' => $questionnaire->hash_id,
        ])
    );

    $response->assertOk();
    \Illuminate\Support\Facades\Notification::assertSentTo([$user], QuestionnaireAttachedToUser::class);

    $attachedQuestionnaire = UserQuestionnaire::where([
        ['user_id', $user->id],
        ['questionnaire_id', $questionnaire->id],
    ])->first();

    $expectedExpiredAtTime = now()->addMinutes($questionnaire->allocated_time * 2);

    // Expired at time must be twice the allocated time from now
    expect((int) $expectedExpiredAtTime->diffInMinutes($attachedQuestionnaire->expires_at, true) === 0)->toBeTrue();
})->group('administrative/users/questionnaires/attach');
