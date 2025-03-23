<?php

namespace App\Filament\Resources\SeatPlanResource\Pages;

use App\Filament\Resources\SeatPlanResource;
use App\Models\Seat;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EditSeats extends Page
{
    use InteractsWithRecord;

    protected static string $resource = SeatPlanResource::class;

    protected static string $view = 'filament.resources.seat-plan-resource.pages.edit-seats';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit-details')
                ->label('Edit details')
                ->icon('heroicon-m-pencil-square')
                ->url(fn(Model $record): string => EditSeatPlan::getUrl(['record' => $record])),
            ActionGroup::make([
                Action::make('addRowStart')
                    ->label('Add Row (Start)')
                    ->action(function (EditSeats $livewire) {
                        Seat::where('seat_plan_id', $livewire->record->id)
                            ->increment('row');

                        $livewire->record->increment('rows');

                        for ($j=1; $j <= $livewire->record->columns; $j++) { 
                            $livewire->record->seats()->create([
                                'row' => 1,
                                'column' => $j
                            ]);
                        }

                        $livewire->dispatch('refreshSeats');
                    }),
                Action::make('addRowEnd')
                    ->label('Add Row (End)')
                    ->action(function (EditSeats $livewire) {
                        $livewire->record->increment('rows');

                        for ($j=1; $j <= $livewire->record->columns; $j++) { 
                            $livewire->record->seats()->create([
                                'row' => $livewire->record->rows,
                                'column' => $j
                            ]);
                        }

                        $livewire->dispatch('refreshSeats');
                    }),
                Action::make('addColumnStart')
                    ->label('Add Column (Start)')
                    ->action(function (EditSeats $livewire) {
                        Seat::where('seat_plan_id', $livewire->record->id)
                            ->increment('column');
                        $livewire->record->increment('columns');

                        for ($j=1; $j <= $livewire->record->rows; $j++) { 
                            $livewire->record->seats()->create([
                                'row' => $j,
                                'column' => 1
                            ]);
                        }

                        $livewire->dispatch('refreshSeats');
                    }),
                Action::make('addColumnEnd')
                    ->label('Add Column (End)')
                    ->action(function (EditSeats $livewire) {
                        $livewire->record->increment('columns');

                        for ($j=1; $j <= $livewire->record->columns; $j++) { 
                            $livewire->record->seats()->create([
                                'row' => $j,
                                'column' => $livewire->record->columns
                            ]);
                        }

                        $livewire->dispatch('refreshSeats');
                    }),
                Action::make('removeRowStart')
                    ->label('Remove Row (Start)')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (EditSeats $livewire) {
                        if ($livewire->record->rows > 1) {
                            $livewire->record->decrement('rows');
                            Seat::where('seat_plan_id', $livewire->record->id)
                                ->where('row', 1)
                                ->delete();

                            Seat::where('seat_plan_id', $livewire->record->id)
                                ->decrement('row');

                            $livewire->dispatch('refreshSeats');
                        }
                    }),
                Action::make('removeRowEnd')
                    ->label('Remove Row (End)')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (EditSeats $livewire) {
                        if ($livewire->record->rows > 1) {
                            $livewire->record->decrement('rows');
                            Seat::where('seat_plan_id', $livewire->record->id)
                                ->where('row', $livewire->record->rows + 1)
                                ->delete();

                            $livewire->dispatch('refreshSeats');
                        }
                    }),
                Action::make('removeColumnStart')
                    ->label('Remove Column (Start)')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (EditSeats $livewire) {
                        if ($livewire->record->columns > 1) {
                            $livewire->record->decrement('columns');
                            Seat::where('seat_plan_id', $livewire->record->id)
                                ->where('column', 1)
                                ->delete();

                            Seat::where('seat_plan_id', $livewire->record->id)
                                ->decrement('column');

                            $livewire->dispatch('refreshSeats');
                        }
                    }),
                Action::make('removeColumnEnd')
                    ->label('Remove Column (End)')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (EditSeats $livewire) {
                        if ($livewire->record->columns > 1) {
                            $livewire->record->decrement('columns');
                            Seat::where('seat_plan_id', $livewire->record->id)
                                ->where('column', $livewire->record->columns + 1)
                                ->delete();

                            $livewire->dispatch('refreshSeats');
                        }
                    }),
            ])
                ->label('Edit capacity')
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
                ->button(),
            Action::make('clearSeats')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (EditSeats $livewire) {
                    $livewire->record->seats()->update(['student' => null]);
                    $livewire->dispatch('refreshSeats');
                }),
        ];
    }

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string | Htmlable
    {
        return 'Edit Seats of ' . $this->getRecord()->section->name . ' - ' . $this->getRecord()->subject;
    }
}