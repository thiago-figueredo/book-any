<?php

namespace App\Http\Controllers;

use App\Exceptions\EventFinishedException;
use App\Exceptions\EventFullException;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function store(Event $event): JsonResponse
    {
        throw_if($event->isFinished(), EventFinishedException::class);
        throw_if($event->isFull(), EventFullException::class);

        $reservation = $event->reservations()->create();

        return response()->json(['data' => $reservation], JsonResponse::HTTP_CREATED);
    }
}
