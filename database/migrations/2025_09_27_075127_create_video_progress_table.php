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
       Schema::create('video_progress', function (Blueprint $table) {
            $table->integer('progress_id')->primary();
            $table->integer('user_id');
            $table->integer('video_id');
            $table->tinyInteger('progress');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('video_id')->references('video_id')->on('videos')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_progress');
    }
};
