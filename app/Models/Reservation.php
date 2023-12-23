<?php

namespace App\Models;

use App\Constants\ReservationStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends BaseModel
{
    protected $attributes = [
        'status' => ReservationStatus::OPEN,
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($reservation) {
            $reservation->event()->increment('participants_count');
        });
    }
}
