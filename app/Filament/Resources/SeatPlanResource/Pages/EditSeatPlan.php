<?php

namespace App\Filament\Resources\SeatPlanResource\Pages;

use App\Filament\Actions\DownloadSeatPlanAction;
use App\Filament\Resources\SeatPlanResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSeatPlan extends EditRecord
{
    protected static string $resource = SeatPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editSeats')
                ->url(fn (Model $record): string => EditSeats::getUrl(['record' => $record]))
                ->icon('heroicon-m-cursor-arrow-rays'),
            DownloadSeatPlanAction::make()
                ->record($this->getRecord()), 
            Actions\DeleteAction::make(),
        ];
    }
}
