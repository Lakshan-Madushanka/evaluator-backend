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

class ShowEvaluationController
{
    public function __invoke(Evaluation $evaluation): EvaluationResource
    {
        $evaluation = $evaluation->load('userQuestionnaire');


        return new EvaluationResource($evaluation);
    }
}
