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
        Schema::create('challenge_options', function (Blueprint $table) {
            $table->integer('option_id')->primary();
            $table->integer('challenge_id');
            $table->char('option_label', 1);
            $table->text('option_text')->nullable();
            $table->integer('object_id')->nullable();

            $table->foreign('challenge_id')->references('challenge_id')->on('challenges')->restrictOnDelete();
            $table->foreign('object_id')->references('object_id')->on('objects')->nullOnDelete();
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_options');
    }
};
