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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // 🔹 TENANCY
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('doctor_id');

            // 🔹 PATIENT (GUEST ALLOWED)
            $table->string('patient_name');
            $table->string('patient_email')->index();
            $table->string('patient_phone')->index();
            $table->unsignedTinyInteger('age')->nullable(); // ✅ FIXED
            $table->text('cause')->nullable();
            // 🔹 BOOKING TIME
            $table->date('booking_date')->index();
            $table->time('start_time');
            $table->time('end_time')->nullable();

            // 🔹 STATUS FLOW
            $table->enum('status', [
                'unverified',
                'pending',
                'accepted',
                'rejected',
                'cancelled'
            ])->default('unverified')->index();

            // 🔹 APPROVAL
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            // 🔹 SECURE TOKEN (USER + EMAIL + DOCTOR ACTION)
            $table->string('action_token')->unique();

            $table->timestamps();

            /* ===============================
               INDEXES (PERFORMANCE)
            =============================== */

            // Critical for slot availability
            $table->index(['doctor_id', 'booking_date'], 'doctor_date_idx');

            // Multi-tenant filtering
            $table->index(['hospital_id', 'booking_date'], 'hospital_date_idx');

            /* ===============================
               FOREIGN KEYS (INTEGRITY)
            =============================== */

            $table->foreign('hospital_id')
                ->references('id')
                ->on('hospitals')
                ->cascadeOnDelete();

            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctors')
                ->cascadeOnDelete();

            $table->foreign('approved_by')
                ->references('id')
                ->on('doctors')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
