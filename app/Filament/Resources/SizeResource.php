<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SizeResource\Pages;
use App\Models\Size;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class SizeResource extends Resource
{
    protected static ?string $model = Size::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationGroup = 'Marketplace';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Select::make('size_type_id')
                    ->relationship('sizeType', 'name')
                    ->nullable()
                    ->label('Size Type'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('sizeType.name')->label('Size Type')->sortable(),
                TextColumn::make('created_at')->dateTime('d/m/Y')->sortable(),
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
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSizes::route('/'),
            'create' => Pages\CreateSize::route('/create'),
            'edit' => Pages\EditSize::route('/{record}/edit'),
        ];
    }
}
