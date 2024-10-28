<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Constants\ApiConstants;
use App\Constants\AppConstants;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\Auth\LoginService;
use App\Http\Controllers\Controller;
use App\Services\Auth\ReferralService;
use Illuminate\Support\Facades\Validator;
use App\Services\Auth\RegistrationService;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'bail|required|min:3',
                'last_name' => 'bail|required|min:3',
                'email' => 'bail|required|email|unique:users,email',
                'password' => 'bail|required|string|min:8',
                'confirm_password' => 'bail|required|string|same:password',
                'phone' => 'bail|required|string',
                'gender' => 'bail|required|string',
                'date_of_birth' => 'bail|required|date',
                ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();

            $user = RegistrationService::createUser(
                $data["first_name"],
                $data["last_name"],
                $data["email"],
                $data["password"],
                $data['phone'],
                $data['gender'],
                $data['date_of_birth']
            );

            DB::commit();

            return ApiHelper::validResponse("Registration successful..", LoginService::apiAuthorized($user));
        } catch (ValidationException $e) {
            DB::rollback();
            $message = $e->getMessage();
            return  ApiHelper::inputErrorResponse($message, ApiConstants::VALIDATION_ERR_CODE, $request, $e);
        } catch (\Exception $e) {
            DB::rollback();
            $message = 'Something went wrong while processing your request.';
            return  ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
        }
    }
}



