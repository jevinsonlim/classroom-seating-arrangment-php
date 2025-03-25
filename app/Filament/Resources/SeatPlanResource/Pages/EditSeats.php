<?php

namespace App\Filament\Resources\SeatPlanResource\Pages;

use App\Filament\Actions\DownloadSeatPlanAction;
use App\Filament\Resources\SeatPlanResource;
use App\Models\Seat;
use App\Models\SeatPlanLog;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
                ->hiddenLabel()
                ->tooltip('Edit details')
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

                        SeatPlanLog::create([
                            'seat_plan_id' => $livewire->record->id,
                            'details' => 'New row inserted at the start'
                        ]);

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

                        SeatPlanLog::create([
                            'seat_plan_id' => $livewire->record->id,
                            'details' => 'New row added'
                        ]);

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

                        SeatPlanLog::create([
                            'seat_plan_id' => $livewire->record->id,
                            'details' => 'New column inserted at the start'
                        ]);

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

                        SeatPlanLog::create([
                            'seat_plan_id' => $livewire->record->id,
                            'details' => 'New column added'
                        ]);

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

                            SeatPlanLog::create([
                                'seat_plan_id' => $livewire->record->id,
                                'details' => 'First row was removed'
                            ]);

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

                            SeatPlanLog::create([
                                'seat_plan_id' => $livewire->record->id,
                                'details' => 'Last row was removed'
                            ]);
    
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

                            SeatPlanLog::create([
                                'seat_plan_id' => $livewire->record->id,
                                'details' => 'First column was removed'
                            ]);

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

                            SeatPlanLog::create([
                                'seat_plan_id' => $livewire->record->id,
                                'details' => 'Last column was removed'
                            ]);

                            $livewire->dispatch('refreshSeats');
                        }
                    }),
            ])
                ->label('Edit capacity')
                ->hiddenLabel()
                ->tooltip('Edit capacity')
                ->icon('heroicon-m-arrows-pointing-out')
                ->color('primary')
                ->button(),
            Action::make('massAssignment')
                ->label('Mass Assignment')
                ->hiddenLabel()
                ->tooltip('Mass Assignment')
                ->color('primary')
                ->icon('heroicon-m-arrow-up-on-square-stack')
                ->form([
                    Placeholder::make('Instructions:')
                        ->content(fn (): string => <<<MESSAGE
                            Please upload a list of students that will be assigned to the seats. Marked seats will be updated first.
                            Overflow students will be assigned to the first vacant seat.
                            MESSAGE
                        ),
                    FileUpload::make('students_list')
                        ->acceptedFileTypes(['text/plain'])
                        ->maxSize(1024)
                        ->required()
                        ->storeFiles(false),
                    Radio::make('update_option')
                        ->options([
                            'append' => 'Append',
                            'override' => 'Override',
                        ])
                        ->descriptions([
                            'override' => 'Assign students starting from the first marked seat (vacant or not).',
                            'append' => 'Assign students starting from the first vacant marked seat.',
                        ])
                        ->default('append'),
                    Radio::make('sorting_option')
                        ->options([
                            'alphabetical-asc' => 'Alphabetical (A-Z)',
                            'alphabetical-desc' => 'Alphabetical (Z-A)',
                            'randomized' => 'Randomized',
                        ])
                        ->helperText('Sort the students list before assignment')
                ])
                ->action(function (EditSeats $livewire, array $data) {  
                    $studentsList = $data['students_list']->get();
                    $studentsList = explode("\n", $studentsList);
                    
                    $assignableSeats = Seat::where('seat_plan_id', $livewire->record->id)
                        ->select('id', 'row', 'column')
                        ->when($data['update_option'] === 'append', fn ($query) => $query->whereNull('student'))
                        ->orderBy('is_occupied_on_template', 'desc')
                        ->orderBy('row')
                        ->orderBy('column')
                        ->get();

                    collect($studentsList)
                        ->filter(fn ($name) => strlen(trim($name)))
                        ->when($data['sorting_option'] === 'alphabetical-asc', fn (Collection $collection) => $collection->sort()->values())
                        ->when($data['sorting_option'] === 'alphabetical-desc', fn (Collection $collection) => $collection->sortDesc()->values())
                        ->when($data['sorting_option'] === 'randomized', fn (Collection $collection) => $collection->shuffle())
                        ->each(function ($name, $index) use ($livewire, $assignableSeats) {
                            $seat = $assignableSeats->slice($index, 1)->first();

                            if (!$seat) {
                                return;
                            }

                            $seat->student = $name;
                            $seat->save();

                            SeatPlanLog::create([
                                'seat_plan_id' => $livewire->record->id,
                                'details' => 'Student ' . $name . ' mass assigned to seat ' . $seat->row . '-' . $seat->column
                            ]);
                        });

                    Notification::make()
                        ->title('Students list assigned successfully')
                        ->success()
                        ->send();

                    $livewire->dispatch('refreshSeats');
                }),
            DownloadSeatPlanAction::make()
                ->record($this->getRecord())
                ->hiddenLabel()
                ->tooltip('Download Seat Plan'), 
            Action::make('clearSeats')
                ->color('danger')
                ->icon('heroicon-m-document-minus')
                ->hiddenLabel()
                ->tooltip('Clear Seats')
                ->requiresConfirmation()
                ->action(function (EditSeats $livewire) {
                    $livewire->record->seats()->update(['student' => null]);

                    SeatPlanLog::create([
                        'seat_plan_id' => $livewire->record->id,
                        'details' => 'All seats were cleared'
                    ]);

                    Notification::make()
                        ->title('Seats cleared')
                        ->success()
                        ->send();

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