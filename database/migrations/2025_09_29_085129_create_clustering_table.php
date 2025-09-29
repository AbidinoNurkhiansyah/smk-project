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
    {Schema::create('clustering', function (Blueprint $table) {
            $table->integer('clustering_id')->primary();
            $table->integer('user_id');
            $table->integer('class_id');
            $table->enum('cluster_label', ['rajin','butuh bimbingan']);
            $table->timestamp('assigned_at')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('class_id')->references('class_id')->on('classes')->restrictOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clustering');
    }
};
