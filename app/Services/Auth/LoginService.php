<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Http\Resources\Account\UserResource;


class LoginService
{

    public static function newLogin(User $user , bool $setSession = true)
    {
        $user->update([
            "email_verified_at" => now()
        ]);
    }


    public static function apiAuthorized(User $user)
    {
        $token = $user->createToken("default")->plainTextToken;
        self::newLogin($user , false);

        return [
            'token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource($user),
        ];
    }
}
