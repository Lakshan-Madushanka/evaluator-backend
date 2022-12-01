<?php

namespace App\Http\Controllers\Api\V1\Administrative\Question;

use App\Enums\Difficulty;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Contracts\Database\Eloquent\Builder;
use ReflectionEnumBackedCase;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexQuestionController extends Controller
{
    public function __invoke(): \TiMacDonald\JsonApi\JsonApiResourceCollection
    {
        $questions = QueryBuilder::for(Question::class)
            ->with(['categories', 'images'])
            ->withCount('answers')
            ->allowedFilters([
                AllowedFilter::callback('content', function (Builder $query, $value) {
                    $query->whereFullText('text', $value);
                }),
                AllowedFilter::callback('hardness', function (Builder $query, $value) {
                    $query->where(
                        'difficulty',
                        // This will return enum value by its name ex: SUPER_ADMIN return 1
                        (new ReflectionEnumBackedCase(Difficulty::class, $value))->getBackingValue()
                    );
                }),
                'categories.name',
            ])
            ->defaultSort('-id')
            ->allowedSorts('created_at')
            ->jsonPaginate();

        return QuestionResource::collection($questions);
    }
}
