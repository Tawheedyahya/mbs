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
            if (!Schema::hasColumn('hospitals', 'summary_flow_id')) {
                $table->string('summary_flow_id')->nullable()
                      ->comment('Speedbots flow ID for daily 8AM appointment summary');
            }
            if (!Schema::hasColumn('hospitals', 'summary_field_id')) {
                $table->string('summary_field_id')->nullable()
                      ->comment('Speedbots custom field ID for daily summary text e.g. 915759');
            }
            if (!Schema::hasColumn('hospitals', 'summary_whatsapp')) {
                $table->string('summary_whatsapp')->nullable()
                      ->comment('WhatsApp number(s) for daily 8AM appointment summary — comma separated');
            }
            if (!Schema::hasColumn('hospitals', 'summary_email')) {
                $table->string('summary_email')->nullable()
                      ->comment('Email(s) for daily 8AM appointment summary — comma separated');
            }
            // Weekly financial report recipients
            if (!Schema::hasColumn('hospitals', 'report_email')) {
                $table->string('report_email')->nullable()
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