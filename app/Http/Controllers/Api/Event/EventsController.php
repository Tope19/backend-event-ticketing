<?php

namespace App\Http\Controllers\Api\Event;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Constants\ApiConstants;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Event\EventService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EventsController extends Controller
{
    public function list(Request $request)
    {
        try {
            $data = EventService::list();
            return ApiHelper::validResponse("Event information retrieved successfully!", $data);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return ApiHelper::problemResponse($message, ApiConstants::SERVER_ERR_CODE, $request, $e);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'bail|required|string',
                'category_id' => 'bail|required|exists:event_categories,id',
                'banner_image' => 'bail|required|string',
                'description' => 'bail|required|string',
                'venue' => 'bail|required|string',
                'event_date' => 'bail|required|string',
                ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();
            $data['slug'] = \Str::slug($data['name']);
            $response = EventService::create($data);
            DB::commit();
            return ApiHelper::validResponse("Event Created successful..", $response);
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



