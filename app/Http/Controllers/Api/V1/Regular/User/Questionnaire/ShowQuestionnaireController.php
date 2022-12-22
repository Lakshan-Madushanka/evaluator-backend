<?php

namespace App\Http\Controllers\Api\V1\Regular\User\Questionnaire;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Questionnaire;
use App\Models\UserQuestionnaire;
use Spatie\QueryBuilder\QueryBuilder;

class ShowQuestionnaireController extends Controller
{
    public function __invoke(string $code)
    {
        $questionnaireId = UserQuestionnaire::query()
            ->select('questionnaire_id')
            ->available($code)
            ->value('questionnaire_id');

        if (is_null($questionnaireId)) {
            return QuestionResource::collection([]);
        }

        $questionnaire = Questionnaire::query()
            ->where('id', $questionnaireId)
            ->first();

        $questions = QueryBuilder::for($questionnaire?->questions())
            ->inRandomOrder()
            ->allowedIncludes(['images', 'onlyAnswers.images'])
            ->jsonPaginate();

        return QuestionResource::collection($questions);
    }
}
