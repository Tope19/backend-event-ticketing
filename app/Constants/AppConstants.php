<?php

namespace App\Constants;

class AppConstants
{
    const REGISTER_BONUS = 250000;
    const ZERO = 0;
    const ONE = 1;

    const MAX_PROFILE_PIC_SIZE = 2048;

    const MALE = 'Male';
    const FEMALE = 'Female';
    const OTHERS = 'Others';


    const GENDERS = [
        self::MALE,
        self::FEMALE,
        self::OTHERS,
    ];

    const PILL_CLASSES = [
        StatusConstants::COMPLETED => "success",
        StatusConstants::PENDING => "primary",
        StatusConstants::PROCESSING => "info",
        StatusConstants::CANCELLED => "danger",
        StatusConstants::ACTIVE => "success",
        StatusConstants::INACTIVE => "warning",
        StatusConstants::DELETED => "danger",
        TransactionConstants::DEBIT => "danger",
        TransactionConstants::CREDIT => "success",
    ];

    const CLIENT_ROLE = 'Customer';
    const ARTISAN_ROLE = 'Artisan';
    const ADMIN_ROLE = 'Admin';
    const DEFAULT_PASSWORD = 'password';

    // const ARTISAN_VERIFICATION_EMAIL = 'verify@letsgetusorted.com';
    const ARTISAN_VERIFICATION_EMAIL = 'letsgetusortedy@gmail.com';
    const ADMIN_EMAIL = 'admin@letsgetusorted.com';
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';

    const PENDING = 'PENDING';

    // Months
    const JANUARY = '01';
    const FEBRUARY = '02';
    const MARCH = '03';
    const APRIL = '04';
    const MAY = '05';
    const JUNE = '06';
    const JULY = '07';
    const AUGUST = '08';
    const SEPTEMBER = '09';
    const OCTOBER = '10';
    const NOVEMBER = '11';
    const DECEMBER = '12';

}
