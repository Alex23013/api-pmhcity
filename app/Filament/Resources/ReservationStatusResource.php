<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationStatusResource\Pages;
use App\Models\ReservationStatus;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ReservationStatusResource extends Resource
{
    protected static ?string $model = ReservationStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('display_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('display_name')->searchable(),
                TextColumn::make('created_at')->dateTime('d/m/Y'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservationStatuses::route('/'),
            'create' => Pages\CreateReservationStatus::route('/create'),
            'edit' => Pages\EditReservationStatus::route('/{record}/edit'),
        ];
    }
}
