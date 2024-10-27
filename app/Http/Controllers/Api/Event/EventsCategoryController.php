<?php

namespace App\Http\Controllers\Api\Event;

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
use App\Services\Event\EventCategoryService;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Events\EventsCategoryRequest;

class EventsCategoryController extends Controller
{
    public function list(Request $request)
    {
        try {
            $data = EventCategoryService::list();
            return ApiHelper::validResponse("Category information retrieved successfully!", $data);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
        }
    }

    public function store(EventsCategoryRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->validated();
            $data["slug"] = \Str::slug($data["name"]);

            $response = EventCategoryService::create($data);

            DB::commit();

            return ApiHelper::validResponse("Category created successful..", $response);
        } catch (ValidationException $e) {
            DB::rollback();
            $message = "The given data was invalid.";
            return  ApiHelper::inputErrorResponse($message, ApiConstants::VALIDATION_ERR_CODE, $request, $e);
        } catch (\Exception $e) {
            DB::rollback();
            $message = 'Something went wrong while processing your request.';
            return  ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
        }
    }
}



