<?php

use App\Models\SeatPlan;
use App\Models\SeatPlanTemplate;
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
        Schema::create('seat_plan_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('rows');
            $table->integer('columns');
            $table->timestamps();
        });

        Schema::table('seat_plans', function (Blueprint $table) {
            $table->foreignId('seat_plan_template_id')
                ->nullable()
                ->references('id')
                ->on('seat_plan_templates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seat_plans', function (Blueprint $table) {
            $table->dropForeign(['seat_plan_template_id']);
            $table->dropColumn('seat_plan_template_id');
        });

        Schema::dropIfExists('seat_plan_templates');
    }
};
