<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = Event::create($request->validated());
        return response()->json(['data' => $event], JsonResponse::HTTP_CREATED);
    }
}
