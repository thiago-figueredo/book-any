<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->string('name');
            $table->unsignedInteger('participants_limit')->nullable();
            $table->unsignedInteger('participants_count');
            $table->dateTime('start');
            $table->dateTime('end')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
