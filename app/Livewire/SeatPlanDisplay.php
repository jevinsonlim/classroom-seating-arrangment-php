<?php

namespace App\Livewire;

use App\Models\Seat;
use App\Models\SeatPlan;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Log;

class SeatPlanDisplay extends Component implements HasForms
{
    use InteractsWithForms;

    public SeatPlan $seatPlan;
    public $seats;
    public $rows;
    public $columns;
    public $selectedSeats = [];
    public $editingSeatId = null;
    public $editingStudent = '';

    protected $listeners = ['refreshSeats' => 'refreshSeats']; 

    public function mount(SeatPlan $seatPlan)
    {
        $this->seatPlan = $seatPlan;
        $this->seats = $seatPlan->seats->keyBy(function ($seat) {
            return $seat->row . '-' . $seat->column;
        });
        $this->rows = $seatPlan->rows;
        $this->columns = $seatPlan->columns;
    }

    public function refreshSeats()
    {
        $this->seats = $this->seatPlan->seats->keyBy(function ($seat) {
            return $seat->row . '-' . $seat->column;
        });
        $this->rows = $this->seatPlan->rows;
        $this->columns = $this->seatPlan->columns;
        $this->render();
    }


    public function selectSeat($row, $column)
    {
        $key = $row . '-' . $column;

        if (count($this->selectedSeats) < 2) {
            $this->selectedSeats[] = $key;
        }

        if (count($this->selectedSeats) === 2) {
            $this->swapSeats();
            $this->selectedSeats = [];
        }
    }

    public function swapSeats()
    {
        if (count($this->selectedSeats) !== 2) {
            return;
        }

        $seat1Key = $this->selectedSeats[0];
        $seat2Key = $this->selectedSeats[1];

        if (!isset($this->seats[$seat1Key]) || !isset($this->seats[$seat2Key])) {
            return;
        }

        $seat1 = $this->seats[$seat1Key];
        $seat2 = $this->seats[$seat2Key];

        $tempStudent = $seat1->student;
        $seat1->student = $seat2->student;
        $seat2->student = $tempStudent;

        $seat1->save();
        $seat2->save();

        $this->seats[$seat1Key] = $seat1;
        $this->seats[$seat2Key] = $seat2;

        $this->seats = collect($this->seats)->keyBy(function ($seat) {
            return $seat->row . '-' . $seat->column;
        });
    }

    public function editSeat($row, $column)
    {
        Log::debug($this->seats);
        $key = $row . '-' . $column;
        $seat = $this->seats[$key] ?? null;

        if ($seat) {
            $this->editingSeatId = $seat->id;
            $this->editingStudent = $seat->student;
            $this->dispatch('open-modal', id: 'edit-seat-modal');
        }
    }

    public function clearSeat($row, $column)
    {
        $key = $row . '-' . $column;
        $seat = $this->seats[$key] ?? null;
        if ($seat) {
            $seat->student = null;
            $seat->save();
            $this->seats[$key] = $seat;
        }
    }

    public function updateSeatStudent()
    {
        if ($this->editingSeatId) {
            $seat = Seat::find($this->editingSeatId);
            if ($seat) {
                $seat->student = $this->editingStudent;
                $seat->save();
                $this->seats[$seat->row . '-' . $seat->column]->student = $this->editingStudent;
            }
        }
        $this->dispatch('close-modal', id: 'edit-seat-modal');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('editingStudent')
                    ->label('Student Name'),
            ])
            ->statePath('editingStudent');
    }

    public function render()
    {
        return view('livewire.seat-plan-display');
    }
}