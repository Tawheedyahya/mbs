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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hospital_id')
                ->constrained('hospitals')
                ->cascadeOnDelete();

            $table->string('name');

            $table->enum('gender', ['male', 'female']);

            $table->string('profile_photo')->nullable();

            // ✅ THIS IS THE ONLY CORRECT FK DEFINITION
            $table->foreignId('specialization_id')
                ->constrained('specializations')
                ->cascadeOnDelete();
            $table->string('doctor_code');
            $table->unsignedTinyInteger('experience_years');

            $table->string('qualification');
            $table->string('phone');

            $table->timestamps();

            $table->unique(['hospital_id', 'phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
