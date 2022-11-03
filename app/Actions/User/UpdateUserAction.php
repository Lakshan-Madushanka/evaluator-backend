<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    /**
     * @param  array  $validatedInputs
     * @param  User  $user
     * @return bool
     */
    public static function execute(array $validatedInputs, User $user): bool
    {
        if (isset($validatedInputs['password'])) {
            $validatedInputs['password'] = Hash::make($validatedInputs['password']);
        }

        return $user->update($validatedInputs);
    }
}
