<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 7: pet care records + intake metadata on pets.
     */
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->date('intake_date')->nullable()->after('date');
            $table->text('intake_notes')->nullable()->after('intake_date');
            $table->string('microchip')->nullable()->after('intake_notes');
        });

        Schema::create('pet_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained('pets')->cascadeOnDelete();
            $table->enum('type', ['vaccination', 'vet_visit', 'grooming', 'intake', 'note']);
            $table->string('title');
            $table->text('details');
            $table->date('record_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_records');
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['intake_date', 'intake_notes', 'microchip']);
        });
    }
};
