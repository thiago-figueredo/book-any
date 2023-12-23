<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ReservationController;

Route::name('events.')->group(function () {
    Route::post('/events', [EventController::class, 'store'])->name('store');
});

Route::name('reservations.')->group(function () {
    Route::post('/reservations/{event}', [ReservationController::class, 'store'])->name('store');
});

Route::name('participants.')->group(function () {
    Route::post('/participants', [ParticipantController::class, 'store'])->name('store');
});
