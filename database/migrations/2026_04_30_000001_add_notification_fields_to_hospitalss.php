<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            // Daily appointment summary recipients
            if (!Schema::hasColumn('hospitals', 'new_booking_notify_phone')) {
                $table->string('new_booking_notify_phone')->nullable()->after('completed_flow_id')
                      ->comment('Phone number(s) to notify on new booking');
            }
            if (!Schema::hasColumn('hospitals', 'new_booking_flow_id')) {
                $table->string('new_booking_flow_id')->nullable()->after('new_booking_notify_phone')
                      ->comment('Speedbots flow ID to trigger on new booking');
            }
            if (!Schema::hasColumn('hospitals', 'new_booking_field_id')) {
                $table->string('new_booking_field_id')->nullable()->after('new_booking_flow_id')
                      ->comment('Speedbots custom field ID for new booking notification text');
            }
            if (!Schema::hasColumn('hospitals', 'summary_field_id')) {
                $table->string('summary_field_id')->nullable()->after('summary_flow_id')
                      ->comment('Speedbots custom field ID for daily summary text');
            }
            if (!Schema::hasColumn('hospitals', 'summary_flow_id')) {
                $table->string('summary_flow_id')->nullable()->after('completed_flow_id')
                      ->comment('Speedbots flow ID for daily 8AM appointment summary');
            }
            if (!Schema::hasColumn('hospitals', 'summary_whatsapp')) {
                $table->string('summary_whatsapp')->nullable()->after('summary_flow_id')
                      ->comment('WhatsApp number(s) for daily 8AM appointment summary — comma separated');
            }
            if (!Schema::hasColumn('hospitals', 'summary_email')) {
                $table->string('summary_email')->nullable()->after('summary_whatsapp')
                      ->comment('Email(s) for daily 8AM appointment summary — comma separated');
            }
            // Weekly financial report recipients
            if (!Schema::hasColumn('hospitals', 'report_email')) {
                $table->string('report_email')->nullable()->after('summary_email')
                      ->comment('Email(s) for weekly financial report — comma separated');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropColumn(['summary_whatsapp', 'summary_email', 'report_email']);
        });
    }
};