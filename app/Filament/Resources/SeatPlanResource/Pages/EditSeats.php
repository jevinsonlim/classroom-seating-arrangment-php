<?php

namespace App\Filament\Resources\SeatPlanResource\Pages;

use App\Filament\Resources\SeatPlanResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Support\Htmlable;

class EditSeats extends Page
{
    use InteractsWithRecord;

    protected static string $resource = SeatPlanResource::class;

    protected static string $view = 'filament.resources.seat-plan-resource.pages.edit-seats';

    protected function getHeaderActions(): array
    {
        return [

            ActionGroup::make([
                Action::make('addRowStart')
                    ->label('Add Row (Start)'),
                Action::make('addRowEnd')
                    ->label('Add Row (End)'),
                Action::make('addColumnStart')
                    ->label('Add Column (Start)'),
                Action::make('addColumnEnd')
                    ->label('Add Column (End)')
            ])
                ->label('Edit capacity')
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
                ->button(),
            Action::make('clearSeats')
                ->color('danger'),
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
