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
        DB::statement("ALTER TABLE treatments MODIFY category ENUM('consultation', 'treatment', 'operation', 'other') NOT NULL DEFAULT 'treatment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE treatments MODIFY category ENUM('consultation', 'treatment', 'operation', 'medicine', 'other') NOT NULL DEFAULT 'treatment'");
    }
};
