<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 2: capacity replaces the one-booking-per-slot constraint.
     * Multiple appointments may share a slot up to the configured capacity.
     */
    public function up(): void
    {
        Schema::table('appointments', function ($table) {
            $table->dropUnique(['appointment_date', 'time_slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function ($table) {
            $table->unique(['appointment_date', 'time_slot']);
        });
    }
};
