<?php

namespace App\Services\Booking\Client;

use App\Constants\ApiConstants;
use App\Constants\StatusConstants;
use App\Exceptions\Booking\BookingException;
use App\Http\Requests\Booking\CancelBookingRequest;
use App\Http\Requests\Booking\ClientBookingRequest;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\QueryBuilders\Booking\BookingQueryBuilder;
use App\Services\Notifications\Booking\BookingNotificationService;
use Illuminate\Support\Facades\Validator;

class BookingService
{

    public static function book(array $data)
    {
        $request = new ClientBookingRequest;
        $validator = Validator::make($data, $request->rules());
        $data = $validator->validated();

        $user = auth()->user();
        $service = Service::find($data["service_id"]);

        $data["user_id"] = $user->id;
        $data["reference"] = strtoupper(self::generateReferenceNo());
        $data["category_id"] = $service->category_id;
        $booking = ServiceBooking::create($data);

        BookingNotificationService::newBooking($booking);

        return $booking;
    }

    public static function generateReferenceNo()
    {
        $key = getRandomToken(8, true);
        $check = ServiceBooking::where("reference", $key)->count();
        if ($check > 0) {
            return self::generateReferenceNo();
        }
        return $key;
    }


    public static function list(array $data = [])
    {
        $data["user_id"] = auth()->id();
        return BookingQueryBuilder::filter($data)
            ->paginate($data["pagination"] ?? ApiConstants::PAGINATION_SIZE_API);
    }

    public static function canccel($data)
    {

        $request = new CancelBookingRequest;
        $validator = Validator::make($data, $request->rules());
        $data = $validator->validated();

        $user = auth()->user();
        $booking = ServiceBooking::where("user_id", $user->id)->find($data["booking_id"]);


        if (empty($booking)) {
            throw new BookingException("You are not authorized to perform this action.");
        }

        if (in_array($booking->status, [StatusConstants::CANCELLED])) {
            throw new BookingException("This booking has already been cancelled");
        }

        $booking->update([
            "status" => StatusConstants::CANCELLED
        ]);
        BookingNotificationService::bookingCancelled($booking, $data["comment"]);
        return $booking->refresh();
    }
}
