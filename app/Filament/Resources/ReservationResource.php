<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('last_status')
                    ->label('Status')
                    ->options([
                        'created'   => 'Created',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'paid' => 'Paid',
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('comment')
                    ->label('Comment')
                    ->maxLength(500),

                Forms\Components\Select::make('size_id')
                    ->relationship('size', 'name')
                    ->label('Size')
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->default(1),

                Forms\Components\Select::make('buyer_id')
                    ->relationship('buyer', 'name')
                    ->label('Buyer')
                    ->required(),

                Forms\Components\Select::make('seller_id')
                    ->relationship('seller', 'name')
                    ->label('Seller')
                    ->required(),

                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label('Product')
                    ->required(),

                /*Forms\Components\Select::make('color_id') // when we want to enable color selection
                    ->relationship('color', 'name')
                    ->label('Color')
                    ->nullable(),*/

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
                // TODO: remove pin_delivery from table reservations and from resources
                /*Forms\Components\TextInput::make('pin_delivery')
                    ->label('PIN Delivery')
                    ->maxLength(6),*/
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),

                Tables\Columns\TextColumn::make('last_status')
                    ->label('Status')
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('buyer.name')
                    ->label('Buyer')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Seller')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('size.name')
                    ->label('Size'),

                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),

                /*Tables\Columns\TextColumn::make('color.name') // when we want to enable color selection
                    ->label('Color'),*/

                Tables\Columns\TextColumn::make('price')
                    ->money('usd')
                    ->sortable(),
                
                /*Tables\Columns\TextColumn::make('pin_delivery')
                    ->label('PIN Delivery')
                    ->limit(6),*/

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('last_status')
                    ->options([
                        'created'   => 'Created',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'paid' => 'Paid',
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered',
                    ]),
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
            // Add relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit'   => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
{
    return [
        \App\Filament\Widgets\ReservationsByLastStatus::class,
    ];
}
}
