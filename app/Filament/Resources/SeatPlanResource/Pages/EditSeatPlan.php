<?php

namespace App\Filament\Resources\SeatPlanResource\Pages;

use App\Filament\Resources\SeatPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeatPlan extends EditRecord
{
    protected static string $resource = SeatPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
