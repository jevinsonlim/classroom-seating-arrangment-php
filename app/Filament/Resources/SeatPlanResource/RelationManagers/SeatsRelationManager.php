<?php

namespace App\Filament\Resources\SeatPlanResource\RelationManagers;

use App\Models\SeatPlanLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class SeatsRelationManager extends RelationManager
{
    protected static string $relationship = 'seats';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student')
                    ->maxLength(255)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderBy('row')
                    ->orderBy('column');
            })
            ->recordTitleAttribute('student')
            ->columns([
                Tables\Columns\TextColumn::make('row'),
                Tables\Columns\TextColumn::make('column'),
                Tables\Columns\TextColumn::make('student')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (Model $record, array $data) {
                        SeatPlanLog::create([
                            'seat_plan_id' => $record->seat_plan_id,
                            'details' => 'Student ' . $record->student . ' assigned to seat ' . $record->row . '-' . $record->column,
                        ]);
                    }),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
