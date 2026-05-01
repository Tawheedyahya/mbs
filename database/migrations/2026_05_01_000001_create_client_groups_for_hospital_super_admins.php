<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('client_groups')) {
            Schema::create('client_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('contact_name')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('contact_phone')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'client_group_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('client_group_id')->nullable()->after('hospital_id')->constrained('client_groups')->nullOnDelete();
            });
        }

        if (!Schema::hasTable('client_group_hospital')) {
            Schema::create('client_group_hospital', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_group_id')->constrained('client_groups')->cascadeOnDelete();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['client_group_id', 'hospital_id'], 'uq_client_group_hospital');
                $table->index('hospital_id');
            });
        }

        // Pre-create the client group from the Hello Doctor checklist.
        if (Schema::hasTable('client_groups')) {
            $groupId = DB::table('client_groups')->where('name', 'Klinik Hello Doctor')->value('id');
            if (!$groupId) {
                $groupId = DB::table('client_groups')->insertGetId([
                    'name' => 'Klinik Hello Doctor',
                    'contact_name' => 'Nazirul Razali',
                    'contact_email' => 'nazirul@klinikhellodoctor.com',
                    'contact_phone' => '0173411778',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (Schema::hasTable('hospitals') && Schema::hasTable('client_group_hospital')) {
                $helloDoctorHospitals = DB::table('hospitals')
                    ->where('hospital_name', 'like', '%Hello Doctor%')
                    ->orWhere('hospital_name', 'like', '%Klinik Hello%')
                    ->pluck('id');

                foreach ($helloDoctorHospitals as $hospitalId) {
                    DB::table('client_group_hospital')->updateOrInsert(
                        ['client_group_id' => $groupId, 'hospital_id' => $hospitalId],
                        ['updated_at' => now(), 'created_at' => now()]
                    );
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('client_group_hospital')) {
            Schema::dropIfExists('client_group_hospital');
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'client_group_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('client_group_id');
            });
        }

        Schema::dropIfExists('client_groups');
    }
};
