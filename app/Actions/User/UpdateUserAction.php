<?php

namespace App\Actions\User;

use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public static function execute(UserUpdateRequest $request, User $user): bool
    {
        /** @var array<string> $validatedInputs * */
        $validatedInputs = $request->validated();

        if (isset($validatedInputs['password'])) {
            $validatedInputs['password'] = Hash::make($validatedInputs['password']);
        }

        return $user->update($validatedInputs);
    }
}
