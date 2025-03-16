<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Dom\Text;
use Filament\Forms\Components\Toggle;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->maxLength(500),
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('subcategory_id')
                    ->relationship('subcategory', 'name')
                    ->required(),
                Select::make('material_id')
                    ->relationship('material', 'name')
                    ->nullable(),
                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->nullable(),
                Select::make('status_product_id')
                    ->relationship('status_product', 'name')
                    ->nullable(),
                Select::make('color_id')
                    ->relationship('color', 'name')
                    ->nullable(),
                TextInput::make('size_ids')
                    ->label('Sizes')
                    ->maxLength(255),
                TextInput::make('article_code')
                    ->maxLength(100),
                TextInput::make('pmh_reference_code')
                    ->maxLength(100),
                Toggle::make('is_active')->label('Active')
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('pmh_reference_code')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('user.name')->label('Seller')->sortable(),
                TextColumn::make('category.name')->label('Category')->sortable(),
                TextColumn::make('subcategory.name')->label('Subcategory')->sortable(),
                TextColumn::make('price')->sortable(),
                TextColumn::make('brand.name')->label('Brand')->sortable(),
                TextColumn::make('status_product.name')->label('Status')->sortable(),
                TextColumn::make('material.name')->label('Material')->sortable(),
                TextColumn::make('size_ids')->label('Sizes')->sortable(),
                TextColumn::make('color.name')->label('Color')->sortable(),
                TextColumn::make('article_code')->sortable(),
                BooleanColumn::make('is_active')->label('Active')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                // Add filters if needed
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
