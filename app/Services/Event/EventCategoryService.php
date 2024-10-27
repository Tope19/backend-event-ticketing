<?php

namespace App\Services\Event;

use App\Models\EventCategory;
use App\Constants\ApiConstants;
use App\Constants\AppConstants;

class EventCategoryService
{

    public static function list(array $data = [])
    {
        return EventCategory::where('status', AppConstants::ACTIVE)->paginate(ApiConstants::PAGINATION_SIZE_API);
    }

    public static function create(array $data)
    {
        $data["status"] = AppConstants::ACTIVE;
        return EventCategory::create($data);
    }

}
