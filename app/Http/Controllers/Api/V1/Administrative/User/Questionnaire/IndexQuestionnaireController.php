<?php

namespace App\Http\Controllers\Api\V1\Administrative\User\Questionnaire;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserQuestionnaireResource;
use App\Models\User;
use Illuminate\Http\Request;
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
    public function __invoke(User $user, Request $request): JsonApiResourceCollection
    {
        $questionnaires = QueryBuilder::for($user->questionnairesWithPivotData())
            ->select([
                'questionnaires.id',
                'user_questionnaire.updated_at',
                'user_questionnaire.created_at',
            ])
            ->jsonPaginate();

        return UserQuestionnaireResource::collection($questionnaires);
    }
}
