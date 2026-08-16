<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 4: volunteers and volunteer shifts.
     */
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('availability')->nullable();
            $table->string('skills')->nullable();
            $table->string('interests')->nullable();
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->unsignedInteger('total_hours')->default(0);
            $table->timestamps();
        });

        Schema::create('volunteer_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained('volunteers')->cascadeOnDelete();
            $table->date('date');
            $table->enum('time_slot', ['Morning Session', 'Afternoon Session']);
            $table->unsignedInteger('hours_logged')->default(0);
            $table->string('activity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_shifts');
        Schema::dropIfExists('volunteers');
    }
};
