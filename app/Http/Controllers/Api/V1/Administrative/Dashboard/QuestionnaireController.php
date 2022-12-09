<?php

namespace App\Http\Controllers\Api\V1\Administrative\Dashboard;

use App\Enums\Difficulty;
use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use Illuminate\Http\JsonResponse;

class QuestionnaireController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $easyValue = Difficulty::EASY->value;
        $mediumValue = Difficulty::MEDIUM->value;
        $hardValue = Difficulty::HARD->value;

        $data = Questionnaire::query()
            ->selectRaw('max(no_of_easy_questions) as easy_questions_count')
            ->selectRaw('max(no_of_medium_questions) as medium_questions_count')
            ->selectRaw('max(no_of_hard_questions) as hard_questions_count')
            ->selectRaw('max(allocated_time) as max_allocated_time')
            ->toBase()
            ->get();

        return new JsonResponse(data: $data[0]);
    }
}
