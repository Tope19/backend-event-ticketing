<?php
namespace App\Http\Controllers\Api\Auth;

use App\Constants\ApiConstants;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LogoutController extends Controller{


    public function logout(Request $request)
    {

        try{
            $validator = Validator::make($request->all(), [
                'device_id' => 'bail|nullable|string|max:10|exists:logged_in_devices,id'
            ]);

            if ($validator->fails()){
                throw new ValidationException($validator);
            }

            $user = ApiHelper::auth(true);
            $device_id = $request["device_id"];
            // if(empty($device_id)){
            //     LoggedInDevice::where(["user_id" => $user->id])->delete();
            // }
            // else{
            //     LoggedInDevice::where(["user_id" => $user->id , "id" => $device_id])->delete();
            // }

            // All good so return the token
            return  ApiHelper::validResponse("Logout successful");

        } catch (ValidationException $e) {
			$message = "The given data was invalid.";
			return  ApiHelper::inputErrorResponse($message, ApiConstants::VALIDATION_ERR_CODE, $request, $e);
		} catch (\Exception $e) {
			$message = 'Something went wrong while processing your request.';
			return  ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
		}
    }

}





