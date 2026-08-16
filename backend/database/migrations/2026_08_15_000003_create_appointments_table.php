<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->enum('appointment_type', ['Adopt', 'Donate', 'Visit', 'Volunteer']);
            $table->date('appointment_date');
            $table->enum('time_slot', ['Morning Session', 'Afternoon Session']);
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('mobile_number', 20);
            $table->string('home_address');
            $table->string('email_address');
            $table->enum('status', ['Pending', 'Accepted', 'Cancelled'])->default('Pending');
            $table->string('message', 1000)->default('');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Prevent double-booking a slot (one appointment per date + session)
            $table->unique(['appointment_date', 'time_slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
