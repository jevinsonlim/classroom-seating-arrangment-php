<?php

namespace App\Filament\Resources\SectionResource\RelationManagers;

use App\Filament\Resources\SeatPlanResource\Pages\EditSeatPlan;
use App\Filament\Resources\SeatPlanResource\Pages\EditSeats;
use App\Models\SeatPlan;
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
        return $form
            ->schema([
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('rows')
                        ->required()
                        ->minValue(1)
                        ->default(5),
                    Forms\Components\TextInput::make('columns')
                        ->required()
                        ->minValue(1)
                        ->default(10),
                ])
            ]);
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
