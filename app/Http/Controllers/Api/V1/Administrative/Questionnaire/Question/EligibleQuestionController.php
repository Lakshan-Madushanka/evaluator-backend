<?php

namespace App\Http\Controllers\Api\V1\Administrative\Questionnaire\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

class EligibleQuestionController extends Controller
{
    public function find(Questionnaire $questionnaire, string $questionId, Request $request): QuestionResource|JsonResponse
    {
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

    public function index(Questionnaire $questionnaire): JsonApiResourceCollection
    {
        $questions = QueryBuilder::for(Question::query())
            ->eligible($questionnaire)
            ->withCount('images')
            ->with('categories')
            ->jsonPaginate();

        return QuestionResource::collection($questions);

    }
}
