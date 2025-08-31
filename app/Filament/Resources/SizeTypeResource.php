<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SizeTypeResource\Pages;
use App\Models\SizeType;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\SizeTypeResource\RelationManagers\SizesRelationManager;

class SizeTypeResource extends Resource
{
    protected static ?string $model = SizeType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Marketplace';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
            ]);
    }
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable(),
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

    public static function getRelations(): array
    {
        return [
            SizesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSizeTypes::route('/'),
            'create' => Pages\CreateSizeType::route('/create'),
            'edit' => Pages\EditSizeType::route('/{record}/edit'),
        ];
    }
}
