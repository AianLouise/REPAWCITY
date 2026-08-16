<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 1: pet status lifecycle + appointments linked to a specific pet.
     */
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->enum('status', ['available', 'on_hold', 'adopted', 'deceased'])
                ->default('available')
                ->after('is_featured');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('pet_id')
                ->nullable()
                ->after('appointment_type')
                ->constrained('pets')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pet_id');
        });

        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
