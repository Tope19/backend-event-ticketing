<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\ApiConstants;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Services\Notifications\AppMailerService;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{

    public function change_password(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'old_password' => 'bail|required|string',
                'new_password' => 'bail|required|string|min:6',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = ApiHelper::auth(true);
            if (Hash::check($request->old_password, $user->password)) {
                $hash_new_password = Hash::make($request->new_password);
                $user->update(["password" => $hash_new_password]);
                $message = "Password changed successfully";
                return  ApiHelper::validResponse($message);
            } else {
                return  ApiHelper::problemResponse("Wrong password provided", ApiConstants::BAD_REQ_ERR_CODE);
            }
        } catch (ValidationException $e) {
            $message = "The given data was invalid";
            return  ApiHelper::inputErrorResponse($message, ApiConstants::VALIDATION_ERR_CODE, $request, $e);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return  ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
        }
    }


    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|exists:users,email',
        ]);

        try {
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = User::where(["email" => $request["email"]])->first();
            $pin = rand(123145,787964);

            $user->update(["reset_pin" => $pin]);

            AppMailerService::send([
                "data" => [
                    "user" => $user,
                    "pin" => $pin
                ],
                "to" => $user->email,
                "template" => "emails/auth/forgot-password",
                "subject" => "Forgot Password Request",
            ]);

            return  ApiHelper::validResponse('A reset link has been sent you your email');
        } catch (ValidationException $e) {
            $message = "The given data was invalid";
            return  ApiHelper::inputErrorResponse($message, ApiConstants::VALIDATION_ERR_CODE, $request, $e);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return  ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
        }
    }


    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|exists:users,email',
            'pin' => 'bail|required|string',
            'password' => 'bail|required|string|min:6',
        ]);

        try {
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = User::where(["email" => $request["email"]])->first();
            $pin = $request->pin;

            if ($user->reset_pin != $pin) {
                return  ApiHelper::problemResponse("Incorrect pin provided", ApiConstants::BAD_REQ_ERR_CODE, $request);
            }

            $user->update(["password" => Hash::make($request["password"]), "reset_pin" => null]);

            return  ApiHelper::validResponse('Password reset successful');
        } catch (ValidationException $e) {
            $message = "The given data was invalid";
            return  ApiHelper::inputErrorResponse($message, ApiConstants::VALIDATION_ERR_CODE, $request, $e);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return  ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
        }
    }
}
