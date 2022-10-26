<?php

namespace Tests\Repositories;

use App\Enums\Role;
use App\Models\User;

class UserRepository
{
    public static function getRandomUser(Role $role = Role::REGULAR): User
    {
        return User::whereRole($role->value)->inRandomOrder()->first();
    }
}
