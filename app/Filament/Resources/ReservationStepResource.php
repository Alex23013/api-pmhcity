<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationStepResource\Pages;
use Filament\Tables\Filters\SelectFilter;
use App\Models\ReservationStep;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Reservation;
use App\Models\ReservationStatus;

class ReservationStepResource extends Resource
{
    protected static ?string $model = ReservationStep::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Reservation Steps';
    protected static ?string $pluralLabel = 'Reservation Steps';
    protected static ?string $label = 'Reservation Step';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reservation_id')
                    ->label('Reservation')
                    ->options(Reservation::all()->pluck('id', 'id'))
                    ->required(),
                Forms\Components\Select::make('reservation_status_id')
                    ->label('Reservation Status')
                    ->options(ReservationStatus::all()->pluck('name', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('reservation_id')->label('Reservation ID')->sortable(),
                Tables\Columns\TextColumn::make('reservationStatus.name')->label('Status')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('reservation_id')
                    ->label('Reservation')
                    ->options(Reservation::all()->pluck('id', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservationSteps::route('/'),
            'create' => Pages\CreateReservationStep::route('/create'),
            'edit' => Pages\EditReservationStep::route('/{record}/edit'),
        ];
    }
}
