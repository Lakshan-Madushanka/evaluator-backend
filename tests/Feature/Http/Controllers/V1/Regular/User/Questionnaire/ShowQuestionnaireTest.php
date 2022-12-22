<?php

use App\Models\UserQuestionnaire;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\getJson;

it('it can throttle request', function () {
    // Sending 5 requests
    for ($i = 1; $i <= 5; $i++) {
        getJson(route('api.v1.users.questionnaires.show',
            ['code' => \Illuminate\Support\Str::uuid()]));
    }

    // 6th request should be throttled
    $response = getJson(route('api.v1.users.questionnaires.show',
        ['code' => \Illuminate\Support\Str::uuid()]));

    $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_TOO_MANY_REQUESTS);
})->group('regular/users/questionnaires/show');

it('can show  questionnaire if available for a user', function () {
    $questionnaire = UserQuestionnaire::query()
        ->where('attempts', 0)
        ->first();
    $questionnaire->expires_at = now()->subMinutes(30);
    $questionnaire?->save();
    $questionnaire?->refresh();

    $response = getJson(route('api.v1.users.questionnaires.show',
        ['code' => $questionnaire?->code]));

    $response->assertJson(fn (AssertableJson $json) => $json->has('data')
        ->etc()
    );
})->group('regular/users/questionnaires/show');
