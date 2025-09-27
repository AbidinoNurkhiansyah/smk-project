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
         Schema::create('points', function (Blueprint $table) {
            $table->integer('point_id')->primary();
            $table->integer('user_id');
            $table->integer('total_point')->default(0);
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
