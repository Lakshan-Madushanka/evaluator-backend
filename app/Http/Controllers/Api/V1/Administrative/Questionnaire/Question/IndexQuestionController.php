<?php

namespace App\Http\Controllers\Api\V1\Administrative\Questionnaire\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

class IndexQuestionController extends Controller
{
    public function __invoke(Questionnaire $questionnaire, Request $request): JsonApiResourceCollection
    {
        $questions = QueryBuilder::for($questionnaire->questionsWithPivotData())
            ->selectRaw('*')
            ->withCount(['images', 'answers'])
            ->allowedIncludes('answers.images')
            ->orderBy('difficulty')
            ->jsonPaginate();

        return QuestionResource::collection($questions);
    }
}
