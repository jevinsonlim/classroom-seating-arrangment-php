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
        Schema::create('seat_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')
                ->references('id')
                ->on('sections')
                ->cascadeOnDelete();
            $table->string('subject');
            $table->integer('columns');
            $table->integer('rows');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_plans');
    }
};
