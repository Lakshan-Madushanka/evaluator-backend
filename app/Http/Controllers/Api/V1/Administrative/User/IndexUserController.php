<?php

namespace App\Http\Controllers\Api\V1\Administrative\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

class IndexUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @return JsonApiResourceCollection
     */
    public function __invoke(Request $request): JsonApiResourceCollection
    {
        $users = QueryBuilder::for(User::class)
            ->select(['id', 'email', 'name', 'role', 'created_at'])
            ->allowedFilters(AllowedFilter::exact('role'))
            ->defaultSort('-id')
            ->allowedSorts('created_at', 'role')
            ->jsonPaginate();

        return UserResource::collection($users);
    }
}
