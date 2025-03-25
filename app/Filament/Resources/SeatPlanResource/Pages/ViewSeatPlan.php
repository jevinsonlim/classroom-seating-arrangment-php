<?php

namespace App\Filament\Resources\SeatPlanResource\Pages;

use App\Filament\Actions\DownloadSeatPlanAction;
use App\Filament\Resources\SeatPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSeatPlan extends ViewRecord
{
    protected static string $resource = SeatPlanResource::class;

    public function getHeaderActions(): array
    {
        return [
            DownloadSeatPlanAction::make()
                ->record($this->getRecord()), 
        ];
    }
}
