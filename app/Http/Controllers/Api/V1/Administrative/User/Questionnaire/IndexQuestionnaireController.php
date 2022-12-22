<?php

namespace App\Http\Controllers\Api\V1\Administrative\User\Questionnaire;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserQuestionnaireResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

class IndexQuestionnaireController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @return JsonApiResourceCollection
     */
    public function __invoke(User $user, Request $request)//: JsonApiResourceCollection
    {
        $questionnaires = QueryBuilder::for($user->questionnairesWithPivotData())
            ->select([
                'questionnaires.id',
                'user_questionnaire.id as userQuestionnaireId',
                'user_questionnaire.attempts',
                'user_questionnaire.expires_at',
                'user_questionnaire.updated_at',
                'user_questionnaire.created_at',
            ])
            ->allowedFilters([
                AllowedFilter::callback('attempted', function (Builder $query, $value) {
                    if (Helpers::checkValueIsTrue($value)) {
                        return $query->where('user_questionnaire.attempts', '>', 0);
                    }

                    return $query->where('user_questionnaire.attempts', 0);
                }),
                AllowedFilter::callback('expired', function (Builder $query, $value) {
                    if (Helpers::checkValueIsTrue($value)) {
                        return $query->where('user_questionnaire.expires_at', '>', now());
                    }

                    return $query->where('user_questionnaire.expires_at', '<=', now());
                }),
            ])
            ->defaultSort('-user_questionnaire.id')
            ->allowedSorts([
                AllowedSort::field('created_at', 'user_questionnaire.created_at'),
            ])
            ->jsonPaginate();

        return UserQuestionnaireResource::collection($questionnaires);
    }
}
