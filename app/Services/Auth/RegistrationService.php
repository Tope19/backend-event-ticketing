<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Constants\AppConstants;
use App\Services\Media\FileService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Services\General\IpphoneService;

class RegistrationService
{

    public static function createUser($first_name, $last_name, $email,$password, $phone, $gender, $date_of_birth): User
    {
        $data = array_merge([
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "password" => $password,
            "phone" => $phone,
            "gender" => $gender,
            "date_of_birth" => $date_of_birth,
        ]);
        $data["email_verified_at"] = Carbon::now();
        $data["role"] = AppConstants::CLIENT_ROLE;
        $data["password"] = Hash::make($data['password']);
        // dd($data);

        return User::create($data);
    }


}
