<?php

namespace App\Services\Event;

use App\Constants\ApiConstants;
use App\Constants\AppConstants;
use App\Models\Events;

class EventService
{

    public static function list(array $data = [])
    {
        return Events::with('category')->where('status', AppConstants::ACTIVE)->paginate(ApiConstants::PAGINATION_SIZE_API);
    }

    public static function create(array $data)
    {
        $data["status"] = AppConstants::ACTIVE;
        return Events::create($data);
    }
}
