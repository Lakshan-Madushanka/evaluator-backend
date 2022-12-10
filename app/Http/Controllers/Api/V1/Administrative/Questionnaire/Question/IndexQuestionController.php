<?php

namespace App\Http\Controllers\Api\V1\Administrative\Questionnaire\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

class IndexQuestionController extends Controller
{
    public function __invoke(Questionnaire $questionnaire, Request $request): JsonApiResourceCollection
    {
        $questions = $questionnaire->questions()
            ->withCount(['images', 'answers'])
            ->orderBy('difficulty')->get();

        return QuestionResource::collection($questions);
    }
}
