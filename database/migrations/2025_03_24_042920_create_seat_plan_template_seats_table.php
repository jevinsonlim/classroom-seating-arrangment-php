<?php

use App\Models\SeatPlanTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seat_plan_template_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_plan_template_id')
                ->nullable()
                ->references('id')
                ->on('seat_plan_templates');
            $table->integer('row');
            $table->integer('column');
            $table->boolean('is_occupied')->default(false);
            $table->timestamps();
        });

        $oneSeatApart = SeatPlanTemplate::create([
            'name' => 'One seat apart',
            'rows' => 11,
            'columns' => 11
        ]);

        $threeColumns = SeatPlanTemplate::create([
            'name' => 'Three columns',
            'rows' => 6,
            'columns' => 10
        ]);

        $uShape = SeatPlanTemplate::create([
            'name' => 'U shape',
            'rows' => 11,
            'columns' => 16
        ]);

        collect([$oneSeatApart, $threeColumns, $uShape])->each(function ($seatPlanTemplate) {
            for ($i=1; $i <= $seatPlanTemplate->rows; $i++) { 
                for ($j=1; $j <= $seatPlanTemplate->columns; $j++) { 
                    $seatPlanTemplate->seats()->create([
                        'row' => $i,
                        'column' => $j,
                    ]);
                }
            }
        });

        $result = $oneSeatApart->seats()
            ->whereIn('row', [2, 4, 6, 8, 10])
            ->whereIn('column', [1, 3, 5, 7, 9, 11])
            ->update(['is_occupied' => true]);

        $threeColumns->seats()
            ->whereIn('row', [2, 3, 4, 5, 6])
            ->whereIn('column', [2, 3, 5, 6, 8, 9])
            ->update(['is_occupied' => true]);

        $uShape->seats()
            ->where(function($query) use ($uShape) {
                $query->where('seat_plan_template_id', $uShape->id)
                    ->whereBetween('row', [2, 2])
                    ->whereBetween('column', [3, 14]);
            })
            ->orWhere(function($query) use ($uShape) {
                $query->where('seat_plan_template_id', $uShape->id)
                    ->whereBetween('row', [3, 11])
                    ->whereIn('column', [2, 15]);
            })
            ->update(['is_occupied' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_plan_template_seats');
    }
};
