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
       Schema::create('objects', function (Blueprint $table) {
            $table->integer('object_id')->primary();
            $table->integer('sparepart_id')->nullable();
            $table->integer('tool_id')->nullable();

            $table->foreign('sparepart_id')->references('sparepart_id')->on('spareparts')->restrictOnDelete();
            $table->foreign('tool_id')->references('tool_id')->on('tools')->restrictOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objects');
    }
};
