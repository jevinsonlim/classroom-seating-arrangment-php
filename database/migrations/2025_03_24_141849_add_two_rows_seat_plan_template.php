<?php

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
        $twoRowsTemplate = SeatPlanTemplate::create([
            'name' => 'Two columns',
            'rows' => 9,
            'columns' => 7
        ]);

        for ($i=1; $i <= $twoRowsTemplate->rows; $i++) { 
            for ($j=1; $j <= $twoRowsTemplate->columns; $j++) { 
                $twoRowsTemplate->seats()->create([
                    'row' => $i,
                    'column' => $j,
                ]);
            }
        }

        $twoRowsTemplate->seats()
            ->whereBetween('row', [2, 9])
            ->whereIn('column', [2, 3, 5, 6])
            ->update(['is_occupied' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SeatPlanTemplate::where('name', 'Two columns')->delete();
    }
};
