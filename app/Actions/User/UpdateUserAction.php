<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    /**
     * @param  array<string, mixed>  $validatedInputs
     * @param  User  $user
     * @return bool
     */
    public static function execute(array $validatedInputs, User $user): bool
    {
        if (isset($validatedInputs['password'])) {
            /** @var string $password */
            $password = $validatedInputs['password'];

            $validatedInputs['password'] = Hash::make($password);
        }

        return $user->update($validatedInputs);
    }
}
