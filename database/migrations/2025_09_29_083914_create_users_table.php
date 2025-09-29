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
       Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->string('user_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['siswa','guru','kajur'])->default('siswa');
            $table->integer('class_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('class_id')->references('class_id')->on('classes')->restrictOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
