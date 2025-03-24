<?php

namespace App\Filament\Resources\SeatPlanResource\Pages;

use App\Filament\Resources\SeatPlanResource;
use App\Models\SeatPlanTemplate;
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

    public function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['seat_plan_template_id']) {
            $template = SeatPlanTemplate::find($data['seat_plan_template_id']);

            $data['rows'] = $template->rows;
            $data['columns'] = $template->columns;
        }

        return $data;
    }
}
