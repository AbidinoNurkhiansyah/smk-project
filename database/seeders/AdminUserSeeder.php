<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminId = DB::table('users')->insertGetId([
            'user_id' => 999,
            'user_name' => 'Admin SMK Project',
            'email' => 'admin@smkproject.com',
            'password' => Hash::make('admin123'),
            'role' => 'kajur',
            'class_id' => 1,
            'created_at' => now()
        ]);

        // Create points for admin
        DB::table('points')->insert([
            'user_id' => $adminId,
            'total_point' => 0
        ]);

        // Create guru user
        $guruId = DB::table('users')->insertGetId([
            'user_id' => 998,
            'user_name' => 'Guru SMK Project',
            'email' => 'guru@smkproject.com',
            'password' => Hash::make('admin123'),
            'role' => 'guru',
            'class_id' => 1,
            'created_at' => now()
        ]);

        // Create points for guru
        DB::table('points')->insert([
            'user_id' => $guruId,
            'total_point' => 0
        ]);
    }
}
