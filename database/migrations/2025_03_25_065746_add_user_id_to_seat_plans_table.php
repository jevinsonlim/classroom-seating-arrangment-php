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
        Schema::table('seat_plans', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seat_plans', function (Blueprint $table) {
            $table->dropForeign('seat_plan_template_id');
            $table->dropColumn('user_id');
        });
    }
};
