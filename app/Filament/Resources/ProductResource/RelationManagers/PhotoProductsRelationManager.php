<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;

class PhotoProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'photoProducts';

    protected static ?string $recordTitleAttribute = 'url';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('url')->required()->maxLength(255),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('url')->sortable()->searchable(),
        ]) ->actions([
            EditAction::make(),
        ]);
    }
}
