<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * HelloDoctorSeeder
 *
 * Creates all 8 Klinik Hello Doctor staff users:
 * - 2 Super Admins (HQ — full access to all 6 outlets)
 * - 6 Hospital Admins (one per outlet — scoped to their outlet only)
 *
 * Default password for all accounts: HelloDoctor@2026
 * ⚠️  Ask each staff member to change their password after first login.
 *
 * Run with: php artisan db:seed --class=HelloDoctorSeeder
 */
class HelloDoctorSeeder extends Seeder
{
    public function run(): void
    {
        // ── Lookup hospital IDs by hospital_code ────────────────
        $hospitals = DB::table('hospitals')
            ->whereIn('hospital_code', [
                'WPXENHRYRT4V80UYTIFM', // Bangi
                '0XYJW2HMWQ0GD5RNJZVZ', // Tropicana Aman
                '7NZKWRDBEMOXVWRRFQ1H', // Desa Mentari
                'MGJSNYD0Q30UQYKJLHAQ', // Bukit Jelutong
                'U2OGGHV91EZ1NNIX7LMJ', // Shah Alam
                'F86AF2LWJL4ANSNBNAQP', // Puncak Alam
            ])
            ->pluck('id', 'hospital_code');

        $bangi        = $hospitals['WPXENHRYRT4V80UYTIFM'] ?? null;
        $tropicana    = $hospitals['0XYJW2HMWQ0GD5RNJZVZ'] ?? null;
        $desaMentari  = $hospitals['7NZKWRDBEMOXVWRRFQ1H'] ?? null;
        $bukitJelutong= $hospitals['MGJSNYD0Q30UQYKJLHAQ'] ?? null;
        $shahAlam     = $hospitals['U2OGGHV91EZ1NNIX7LMJ'] ?? null;
        $puncakAlam   = $hospitals['F86AF2LWJL4ANSNBNAQP'] ?? null;

        // ── Default password ─────────────────────────────────────
        $password = Hash::make('HelloDoctor@2026');

        // ── Helper: generate unique api_code ─────────────────────
        $apiCode = fn($prefix) => strtoupper($prefix . substr(md5(uniqid()), 0, 8));

        // ════════════════════════════════════════════════════════
        // SUPER ADMINS — hospital_id = NULL (see all 6 outlets)
        // ════════════════════════════════════════════════════════

        $superAdmins = [
            [
                'name'        => 'En Nazirul',
                'email'       => 'nazirul@klinikhellodoctor.com',
                'phone'       => '0173411778',
                'hospital_id' => null, // HQ — no outlet restriction
            ],
            [
                'name'        => 'Dr Syafiq',
                'email'       => 'drsyafiqsuzuki@klinikhellodoctor.com',
                'phone'       => '0184603889',
                'hospital_id' => null, // HQ — no outlet restriction
            ],
        ];

        foreach ($superAdmins as $admin) {
            $existing = DB::table('users')->where('email', $admin['email'])->first();

            if ($existing) {
                DB::table('users')->where('email', $admin['email'])->update([
                    'name'        => $admin['name'],
                    'role'        => 'super_admin',
                    'hospital_id' => null,
                    'status'      => 1,
                    'updated_at'  => now(),
                ]);
                $this->command->info("✅ Updated super admin: {$admin['name']} ({$admin['email']})");
            } else {
                DB::table('users')->insert([
                    'name'        => $admin['name'],
                    'email'       => $admin['email'],
                    'password'    => $password,
                    'role'        => 'super_admin',
                    'hospital_id' => null,
                    'status'      => 1,
                    'api_code'    => $apiCode('SA'),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $this->command->info("✅ Created super admin: {$admin['name']} ({$admin['email']})");
            }
        }

        // ════════════════════════════════════════════════════════
        // HOSPITAL ADMINS — each scoped to their outlet only
        // ════════════════════════════════════════════════════════

        $hospitalAdmins = [
            [
                'name'        => 'Pn Mastura',
                'email'       => 'klinikhellodoctor.bangi24@gmail.com',
                'phone'       => '01161333391',
                'outlet'      => 'Bangi',
                'hospital_id' => $bangi,
            ],
            [
                'name'        => 'Pn Dianna',
                'email'       => 'klinikhellodoctor.tropicanaaman@gmail.com',
                'phone'       => '01161333381',
                'outlet'      => 'Tropicana Aman',
                'hospital_id' => $tropicana,
            ],
            [
                'name'        => 'Pn Nabilah',
                'email'       => 'klinikhellodoctordesamentari@gmail.com',
                'phone'       => '01161333371',
                'outlet'      => 'Desa Mentari',
                'hospital_id' => $desaMentari,
            ],
            [
                'name'        => 'Cik Ain',
                'email'       => 'hellodoctor.bukitjelutong@gmail.com',
                'phone'       => '0104164646',
                'outlet'      => 'Bukit Jelutong',
                'hospital_id' => $bukitJelutong,
            ],
            [
                'name'        => 'Cik Afia',
                'email'       => 'klinikhellodoctor.shahalams13@gmail.com',
                'phone'       => '01161333351',
                'outlet'      => 'Shah Alam',
                'hospital_id' => $shahAlam,
            ],
            [
                'name'        => 'Cik Syafiqah',
                'email'       => 'klinikhellodoctor.puncakalam@gmail.com',
                'phone'       => '0184703889',
                'outlet'      => 'Puncak Alam',
                'hospital_id' => $puncakAlam,
            ],
        ];

        foreach ($hospitalAdmins as $admin) {
            if (!$admin['hospital_id']) {
                $this->command->warn("⚠️  Skipped {$admin['name']} — hospital '{$admin['outlet']}' not found in DB. Create the hospital first.");
                continue;
            }

            $existing = DB::table('users')->where('email', $admin['email'])->first();

            if ($existing) {
                DB::table('users')->where('email', $admin['email'])->update([
                    'name'        => $admin['name'],
                    'role'        => 'hospital_admin',
                    'hospital_id' => $admin['hospital_id'],
                    'status'      => 1,
                    'updated_at'  => now(),
                ]);
                $this->command->info("✅ Updated hospital admin: {$admin['name']} → {$admin['outlet']} (hospital_id: {$admin['hospital_id']})");
            } else {
                DB::table('users')->insert([
                    'name'        => $admin['name'],
                    'email'       => $admin['email'],
                    'password'    => $password,
                    'role'        => 'hospital_admin',
                    'hospital_id' => $admin['hospital_id'],
                    'status'      => 1,
                    'api_code'    => $apiCode('HA'),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $this->command->info("✅ Created hospital admin: {$admin['name']} → {$admin['outlet']} (hospital_id: {$admin['hospital_id']})");
            }
        }

        // ── Summary ──────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('════════════════════════════════════════');
        $this->command->info('  Hello Doctor Users Setup Complete');
        $this->command->info('════════════════════════════════════════');
        $this->command->info('  Default password: HelloDoctor@2026');
        $this->command->warn('  ⚠️  Ask all staff to change their password after first login!');
        $this->command->info('');
        $this->command->info('  Super Admins (see all 6 outlets):');
        $this->command->info('  → En Nazirul   | nazirul@klinikhellodoctor.com');
        $this->command->info('  → Dr Syafiq    | drsyafiqsuzuki@klinikhellodoctor.com');
        $this->command->info('');
        $this->command->info('  Hospital Admins (own outlet only):');
        $this->command->info('  → Pn Mastura   | Bangi');
        $this->command->info('  → Pn Dianna    | Tropicana Aman');
        $this->command->info('  → Pn Nabilah   | Desa Mentari');
        $this->command->info('  → Cik Ain      | Bukit Jelutong');
        $this->command->info('  → Cik Afia     | Shah Alam');
        $this->command->info('  → Cik Syafiqah | Puncak Alam');
        $this->command->info('════════════════════════════════════════');
    }
}