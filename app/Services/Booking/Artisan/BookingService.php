<?php

namespace App\Services\Booking\Artisan;

use App\Constants\ApiConstants;
use App\Constants\StatusConstants;
use App\Exceptions\Booking\BookingException;
use App\Http\Requests\Booking\BookingResponseRequest;
use App\Models\ServiceBooking;
use App\QueryBuilders\Booking\BookingQueryBuilder;
use App\Services\Notifications\Booking\BookingNotificationService;
use Illuminate\Support\Facades\Validator;

class BookingService
{

    public static function list(array $data = [])
    {
        $data["artisan_id"] = auth()->id();
        return BookingQueryBuilder::filter($data)
            ->paginate($data["pagination"] ?? ApiConstants::PAGINATION_SIZE_API);
    }

    public static function respond($data)
    {
        $request = new BookingResponseRequest();
        $validator = Validator::make($data, $request->rules());
        $data = $validator->validated();
        $status = $data["status"];

        $user = auth()->user();
        $booking = ServiceBooking::where("artisan_id", $user->id)->find($data["booking_id"]);

        if (empty($booking)) {
            throw new BookingException("You are not authorized to perform this action.");
        }

        if (in_array($booking->status, [StatusConstants::CANCELLED])) {
            throw new BookingException("This booking has already been cancelled");
        }

        $booking->update([
            "status" => $status == "Accepted" ? StatusConstants::ACTIVE : $status
        ]);

        BookingNotificationService::requestAction($booking, $status, $data["comment"] ?? null);
        return $booking->refresh();
    }
}
