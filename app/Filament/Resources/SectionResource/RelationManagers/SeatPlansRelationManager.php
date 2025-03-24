<?php

namespace App\Filament\Resources\SectionResource\RelationManagers;

use App\Filament\Resources\SeatPlanResource;
use App\Filament\Resources\SeatPlanResource\Pages\EditSeatPlan;
use App\Filament\Resources\SeatPlanResource\Pages\EditSeats;
use App\Models\SeatPlan;
use App\Models\SeatPlanTemplate;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class SeatPlansRelationManager extends RelationManager
{
    protected static string $relationship = 'seatPlans';

    public function form(Form $form): Form
    {
        return SeatPlanResource::form($form);        
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject')
            ->columns([
                Tables\Columns\TextColumn::make('subject'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        if ($data['seat_plan_template_id']) {
                            $template = SeatPlanTemplate::find($data['seat_plan_template_id']);
                
                            $data['rows'] = $template->rows;
                            $data['columns'] = $template->columns;
                        }

                        $record = $model::create([
                            ...$data,
                            'section_id' => $this->getOwnerRecord()->id
                        ]);

                        $record->prepopulateSeats();

                        return $record;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit details')
                    ->url(fn(Model $record): string => EditSeatPlan::getUrl(['record' => $record])),
                Tables\Actions\Action::make('edit-seats')
                    ->icon('heroicon-m-cursor-arrow-rays')
                    ->url(fn (SeatPlan $record): string => EditSeats::getUrl(['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
