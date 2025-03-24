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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_plan_id')
                ->references('id')
                ->on('seat_plans')
                ->cascadeOnDelete();
            $table->string('student')->nullable();
            $table->integer('row');
            $table->integer('column');
            $table->boolean('is_occupied_on_template')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
