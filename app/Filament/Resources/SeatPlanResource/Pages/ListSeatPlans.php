<?php

namespace App\Filament\Resources\SeatPlanResource\Pages;

use App\Filament\Resources\SeatPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeatPlans extends ListRecords
{
    protected static string $resource = SeatPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
