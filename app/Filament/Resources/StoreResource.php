<?php

namespace App\Filament\Resources;

use App\Models\Store;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\StoreResource\Pages;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;
    protected static ?string $navigationIcon =  'heroicon-o-map';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('name')
                    ->label('Store Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->label('Description')
                    ->maxLength(255),
                TextInput::make('logo')
                    ->label('Logo URL')
                    ->maxLength(255),
                TextInput::make('siret')
                    ->label('SIRET')
                    ->maxLength(255),
                Toggle::make('is_verified')
                    ->label('Verified'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
        ->columns([
            TextColumn::make('user.name')
                ->label('Owner')
                ->sortable(),
            TextColumn::make('name')
                ->label('Store Name')
                ->sortable()
                ->searchable(),
            TextColumn::make('description')
                ->label('Description')
                ->sortable(),
            TextColumn::make('siret')
                ->label('SIRET'),
            BooleanColumn::make('is_verified')
                ->label('Verified'),
            TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime(),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }

}
