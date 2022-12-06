<?php

namespace App\Http\Controllers\Api\V1\Administrative\Answer;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexAnswerController extends Controller
{
    public function __invoke(): \TiMacDonald\JsonApi\JsonApiResourceCollection
    {
        $answers = QueryBuilder::for(Answer::class)
            ->withCount(['images'])
            ->allowedFilters([
                AllowedFilter::callback('text', function (Builder $query, $value) {
                    $query->whereFullText('text', $value);
                }),
            ])
            ->defaultSort('-id')
            ->allowedSorts('created_at')
            ->jsonPaginate();

        return AnswerResource::collection($answers);
    }
}
