<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classes')->insert([
            [
                'class_id' => 1,
                'class_name' => 'TSM X',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 2,
                'class_name' => 'TSM XI',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 3,
                'class_name' => 'TSM XII',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}