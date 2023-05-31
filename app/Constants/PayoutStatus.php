<?php

namespace App\Constants;

class PayoutStatus
{
    const PENDING = 0;
    const PROCESSING = 1;
    const PAID = 2;
    const FAILED = 3;
    const IN_TRANSIT = 4;
    const CANCELED = 5;
    const UNKNOWN_ERROR = 6;

    public static $labels = [
        self::PENDING => 'PENDING',
        self::PAID => 'PAID',
        self::IN_TRANSIT => 'IN_TRANSIT',
        self::CANCELED => 'CANCELED',
        self::FAILED => 'FAILED',
        self::UNKNOWN_ERROR => 'UNKNOWN_ERROR',
        self::PROCESSING => 'PROCESSING'
    ];
}
