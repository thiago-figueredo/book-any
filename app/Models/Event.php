<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends BaseModel
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'participants_limit',
        'participants_count',
        'start',
        'end',
    ];

    protected $casts = [
        'start' => 'datetime:Y-m-d H:i:s',
        'end' => 'datetime:Y-m-d H:i:s',
    ];


    protected $attributes = [
        'participants_count' => 0,
        'is_finished' => false,
    ];

    public function isFinished(): bool
    {
        return $this->is_finished || $this->end?->isPast();
    }

    public function isFull(): bool
    {
        if (is_null($this->participants_limit)) {
            return false;
        }

        return $this->participants_count >= $this->participants_limit;
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function finish(): void
    {
        $this->is_finished = true;
        $this->save();
    }
}
