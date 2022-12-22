<?php

namespace App\Http\Controllers\Api\V1\Regular\User\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\UserQuestionnaire;
use Illuminate\Http\JsonResponse;

class CheckQuestionnaireAvailableController extends Controller
{
    public function __invoke(string $code): JsonResponse
    {
        $exists = UserQuestionnaire::query()
                    ->available($code)
                    ->exists();

        return new JsonResponse(data: ['available' => $exists, 'time' => now()]);
    }
}
