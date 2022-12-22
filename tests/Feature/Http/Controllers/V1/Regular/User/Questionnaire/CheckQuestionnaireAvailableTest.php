<?php

use App\Models\UserQuestionnaire;
use function Pest\Laravel\getJson;

it('it can throttle request', function () {
    // Sending 5 requests
    for ($i = 1; $i <= 5; $i++) {
        getJson(route('api.v1.users.questionnaires.checkAvailable',
            ['code' => \Illuminate\Support\Str::uuid()]));
    }

    // 6th request should be throttled
    $response = getJson(route('api.v1.users.questionnaires.checkAvailable',
        ['code' => \Illuminate\Support\Str::uuid()]));

    $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_TOO_MANY_REQUESTS);
})->group('regular/users/questionnaires/checkAvailable');

it('can check if questionnaire is available for a user', function () {
    $questionnaire = UserQuestionnaire::query()
        ->where('attempts', 0)
        ->first();
    $questionnaire->expires_at = now()->addMinutes(30);
    $questionnaire?->save();
    $questionnaire->refresh();

    $response = getJson(route('api.v1.users.questionnaires.checkAvailable',
        ['code' => $questionnaire?->code]));

    $response->assertOk();
    $response->assertJsonPath('available', true);
})->group('regular/users/questionnaires/checkAvailable');
