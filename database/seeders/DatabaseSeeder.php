<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in order
        $this->call([
            ClassSeeder::class,
        ]);

        // Create user after classes are created
        DB::table('users')->insert([
            'user_id' => 1,
            'user_name' => 'Rega Rahadianz',
            'email' => 'rega@example.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'class_id' => 1,
            'created_at' => now()
        ]);

        // Run video and quiz seeders
        $this->call([
            VideoClassSeeder::class,
            QuizClassSeeder::class,
            QuizKelas10Seeder::class,
            QuizSettingsSeeder::class,
            DefaultDataSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
