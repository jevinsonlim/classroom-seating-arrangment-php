<?php

namespace App\Filament\Resources\SeatPlanResource\Pages;

use App\Filament\Resources\SeatPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateSeatPlan extends CreateRecord
{
    protected static string $resource = SeatPlanResource::class;

    protected function afterCreate(): void
    {
        $this->getRecord()->prepopulateSeats();
    }
}
