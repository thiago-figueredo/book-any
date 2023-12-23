<?php

use App\Constants\ReservationStatus;
use App\Models\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->uuid('event_uuid');
            $table->foreign('event_uuid')->references('uuid')->on('events');

            $table->enum('status', ReservationStatus::all())->default(ReservationStatus::OPEN);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
