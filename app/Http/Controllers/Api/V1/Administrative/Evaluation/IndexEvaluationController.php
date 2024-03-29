<?php

namespace App\Http\Controllers\Api\V1\Administrative\Evaluation;

use App\Http\Filters\BetweenFilter;
use App\Http\Resources\EvaluationResource;
use App\Models\Evaluation;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

class IndexEvaluationController
{
    public function __invoke(User $user): JsonApiResourceCollection
    {
        $results = QueryBuilder::for(Evaluation::query()->with('userQuestionnaire'))
            ->allowedFilters([
                AllowedFilter::scope('user', 'filterByUserId'),
                AllowedFilter::scope('questionnaire', 'filterByQuestionnaireId'),
                AllowedFilter::scope('user_questionnaire', 'filterByUserQuestionnaire'),
                AllowedFilter::custom('marks_percentage', new BetweenFilter()),
            ])
            ->defaultSort('-id')
            ->allowedSorts(
                'time_taken',
                AllowedSort::field('no_of_correct_answers', 'correct_answers'),
                'no_of_answered_questions',
                'marks_percentage',
                'total_points_earned',
                'total_points_allocated',
                'created_at',
            )
            ->jsonPaginate();

        return EvaluationResource::collection($results);
    }
}
