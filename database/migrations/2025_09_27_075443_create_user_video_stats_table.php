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
         Schema::create('user_video_stats', function (Blueprint $table) {
            $table->integer('stats_id')->primary();
            $table->integer('user_id');
            $table->integer('total_videos')->default(0);
            $table->timestamp('last_updated')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_video_stats');
    }
};
