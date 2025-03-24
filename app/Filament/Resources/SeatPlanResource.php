<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeatPlanResource\Pages;
use App\Filament\Resources\SeatPlanResource\Pages\EditSeats;
use App\Filament\Resources\SeatPlanResource\RelationManagers;
use App\Filament\Resources\SeatPlanResource\RelationManagers\LogsRelationManager;
use App\Filament\Resources\SeatPlanResource\RelationManagers\SeatsRelationManager;
use App\Models\SeatPlan;
use App\Models\SeatPlanTemplate;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeatPlanResource extends Resource
{
    protected static ?string $model = SeatPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('section_id')
                    ->relationship('section', 'name')
                    ->required(),
                Forms\Components\TextInput::make('subject')
                    ->required(),
                Forms\Components\Select::make('seat_plan_template_id')
                    ->relationship('template', 'name')
                    ->afterStateUpdated(function (Set $set, $state) {
                        if ($state) {
                            $template = SeatPlanTemplate::find($state);

                            $set('rows', $template->rows);
                            $set('columns', $template->columns);
                        } else {
                            $set('rows', 5);
                            $set('columns', 10);
                        }
                    })
                    ->live(),
                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('rows')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(5)
                            ->disabled(function (Get $get, string $operation) {
                                if ($operation !== 'create') {
                                    return false;
                                }

                                return $get('seat_plan_template_id');
                            }),
                        Forms\Components\TextInput::make('columns')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(10)
                            ->disabled(function (Get $get, string $operation) {
                                if ($operation !== 'create') {
                                    return false;
                                }

                                return $get('seat_plan_template_id');
                            }),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section.name')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),
                Tables\Columns\TextColumn::make('columns')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rows')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit details'),
                Tables\Actions\Action::make('edit-seats')
                    ->icon('heroicon-m-cursor-arrow-rays')
                    ->url(fn(SeatPlan $record): string => EditSeats::getUrl(['record' => $record]))
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SeatsRelationManager::class,
            LogsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeatPlans::route('/'),
            'create' => Pages\CreateSeatPlan::route('/create'),
            'edit' => Pages\EditSeatPlan::route('/{record}/edit'),
            'edit-seats' => Pages\EditSeats::route('/{record}/edit-seats'),
        ];
    }
}
