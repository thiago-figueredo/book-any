<?php

namespace App\Constants;

class ReservationStatus 
{
    const OPEN = 'open';
    const CLOSED = 'closed';

    public static function all(): array
    {
        return [
            self::OPEN,
            self::CLOSED,
        ];
    }
}