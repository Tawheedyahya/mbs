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
        Schema::table('bookings', function (Blueprint $table) {
            // Add reschedule-related columns
            $table->text('reschedule_reason')->nullable()->after('status');
            $table->timestamp('rescheduled_at')->nullable()->after('reschedule_reason');
            $table->unsignedBigInteger('rescheduled_by')->nullable()->after('rescheduled_at');

            // Update status enum to include new statuses (if using enum)
            // If you're using a string column, you can skip this or add a check constraint
          $table->enum('status', [
    'unverified',
    'pending',
    'accepted',
    'rejected',
    'cancelled',
    'no-show',
    'rescheduled'
])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['reschedule_reason', 'rescheduled_at', 'rescheduled_by']);
        });
    }
};
