<?php

namespace App\Livewire;

use App\Models\Seat;
use App\Models\SeatPlan;
use Livewire\Component;

class SeatPlanDisplay extends Component
{
    public SeatPlan $seatPlan;
    public $seats;
    public $rows;
    public $columns;

    public function mount(SeatPlan $seatPlan)
    {
        $this->seatPlan = $seatPlan;
        $this->seats = $seatPlan->seats->keyBy(function ($seat) {
            return $seat->row . '-' . $seat->column;
        });
        $this->rows = $seatPlan->rows;
        $this->columns = $seatPlan->columns;
    }

    public function updateSeatPosition($oldRow, $oldColumn, $newRow, $newColumn)
    {
        $oldKey = $oldRow . '-' . $oldColumn;
        $newKey = $newRow . '-' . $newColumn;

        if (!isset($this->seats[$oldKey])) {
            return; // Seat not found at the old position
        }

        $seat = $this->seats[$oldKey];
        unset($this->seats[$oldKey]);

        // Check if the new position is occupied
        if (isset($this->seats[$newKey])) {
            $swappedSeat = $this->seats[$newKey];
            $swappedSeat->row = $oldRow;
            $swappedSeat->column = $oldColumn;
            $swappedSeat->save();
            $this->seats[$oldRow . '-' . $oldColumn] = $swappedSeat;
        }

        $seat->row = $newRow;
        $seat->column = $newColumn;
        $seat->save();
        $this->seats[$newRow . '-' . $newColumn] = $seat;

        $this->seats = collect($this->seats)->keyBy(function ($seat) {
            return $seat->row . '-' . $seat->column;
        });

    }

    public function render()
    {
        return view('livewire.seat-plan-display');
    }
}