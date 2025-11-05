<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan user_id maksimal
        $maxUserId = DB::table('users')->max('user_id');
        $newUserId1 = $maxUserId + 1;
        $newUserId2 = $maxUserId + 2;

        // Buat sample murid untuk testing
        DB::table('users')->insert([
            [
                'user_id' => $newUserId1,
                'user_name' => 'Ahmad Rizki',
                'email' => 'ahmad@example.com',
                'password' => bcrypt('password'),
                'role' => 'siswa',
                'class_id' => 1,
                'created_at' => now()
            ],
            [
                'user_id' => $newUserId2,
                'user_name' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'password' => bcrypt('password'),
                'role' => 'siswa',
                'class_id' => 2,
                'created_at' => now()
            ]
        ]);

        // Buat data points untuk murid
        DB::table('points')->insert([
            ['user_id' => $newUserId1, 'total_point' => 150],
            ['user_id' => $newUserId2, 'total_point' => 200]
        ]);

        $this->command->info('Sample students created successfully!');
    }
}
