<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->required(),

                Forms\Components\Select::make('type')
                    ->options([
                        'earning'    => 'Earning',
                        'withdrawal' => 'Withdrawal',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('reference_type')
                    ->label('Reference Type')
                    ->disabled(), // usually set by system

                Forms\Components\TextInput::make('reference_id')
                    ->label('Reference ID')
                    ->disabled(), // usually set by system
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'earning',
                        'danger'  => 'withdrawal',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('usd')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference_type')
                    ->label('Reference Type'),

                Tables\Columns\TextColumn::make('reference_id')
                    ->label('Reference ID'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'earning'    => 'Earning',
                        'withdrawal' => 'Withdrawal',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // no relation managers for now
        ];
    }

    public static function getPages(): array
{
    return [
        'index'  => Pages\ListTransactions::route('/'),
        'create' => Pages\CreateTransaction::route('/create'),
        'edit'   => Pages\EditTransaction::route('/{record}/edit'),
    ];
}
}
