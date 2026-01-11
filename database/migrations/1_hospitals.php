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
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();

            // Hospital identity
            $table->string('hospital_name')->unique();
            $table->string('hospital_code')->unique();
            $table->string('hospital_phone')->unique();
            $table->string('hospital_logo')->nullable()->comment('path or url');

            // Admin (primary hospital contact)
            $table->string('admin_name');
            $table->string('admin_phone')->unique();

            // Address
            $table->string('address_line');
            $table->string('address_line2')->nullable();
            $table->string('city')->index();
            $table->string('country')->index();

            // Environment & lifecycle
            $table->boolean('db_status')
                ->default(true)
                ->comment('0=testing,1=production');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
