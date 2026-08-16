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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Dog', 'Cat']);
            $table->string('breed');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('weight');
            $table->string('age');
            $table->date('date')->nullable();
            $table->text('about');
            $table->string('image');
            $table->unsignedTinyInteger('is_featured')->default(0); // 0 = none, 1-4 = featured slot
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
