<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 2: shelter availability calendar — open/closed days + per-session capacity.
     */
    public function up(): void
    {
        Schema::create('shelter_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->boolean('is_open')->default(true);
            $table->unsignedSmallInteger('morning_capacity')->default(10);
            $table->unsignedSmallInteger('afternoon_capacity')->default(10);
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelter_schedules');
    }
};
