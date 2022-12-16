<?php

namespace App\Http\Controllers\Api\V1\Regular\User\Questionnaire;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FindQuestionnaireExistsController extends Controller
{
    public function __invoke(Questionnaire $questionnaire, string $questionId, Request $request): QuestionResource|JsonResponse
    {
        $questionnaireCategoriesIds = $questionnaire->categories()->pluck('categories.id');

        $question = Question::query()
            ->eligible($questionnaire)
            ->wherePrettyId($questionId)
            ->withCount('images')
            ->first();

        if (is_null($question)) {
            return new JsonResponse(data: ['eligible' => false]);
        }

        return new QuestionResource($question);
    }
}
