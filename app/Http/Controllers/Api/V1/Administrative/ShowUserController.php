<?php

namespace App\Http\Controllers\Api\V1\Administrative;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class ShowUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  User  $user
     *
     * @return UserResource
     */
    public function __invoke(User $user): UserResource
    {
        return new UserResource($user);
    }
}
