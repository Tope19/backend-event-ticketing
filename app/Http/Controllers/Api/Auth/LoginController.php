<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Constants\ApiConstants;
use App\Services\Auth\LoginService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\General\IpAddressService;
use App\Http\Resources\Account\UserResource;
use App\Services\General\GeolocationService;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'bail|required|email|exists:users,email',
                'password' => 'bail|required|string|max:255',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                $message = "Invalid credentials";
                return ApiHelper::problemResponse($message, ApiConstants::AUTH_ERR_CODE, $request);
            }

            return ApiHelper::validResponse("Login successful", LoginService::apiAuthorized($user));
        } catch (ValidationException $e) {
            $message = "The given data was invalid.";
            return ApiHelper::inputErrorResponse($message, ApiConstants::VALIDATION_ERR_CODE, $request, $e);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
        }
    }

    public function verify_token(Request $request)
    {
        try {
            $user = auth()->user();
            return ApiHelper::validResponse("Login successful", new UserResource($user));
        } catch (ValidationException $e) {
            $message = "The given data was invalid.";
            return ApiHelper::inputErrorResponse($message, ApiConstants::VALIDATION_ERR_CODE, $request, $e);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
        }
    }
}
