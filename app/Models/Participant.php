<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Participant extends BaseModel
{
    protected $fillable = [
        'age',
        'phone',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
