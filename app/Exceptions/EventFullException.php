<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventFullException extends Exception
{
    public function report(): bool
    {
        return false;
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json(
            self::response(),
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    public static function response(): array
    {
        return ['message' => 'Event is full'];
    }
}
