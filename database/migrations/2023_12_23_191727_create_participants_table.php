<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->unsignedTinyInteger('age');
            $table->string('phone', 20);

            $table->uuid('user_uuid');
            $table->foreign('user_uuid')->references('uuid')
                ->on('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
