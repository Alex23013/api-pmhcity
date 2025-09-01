<?php

namespace App\Filament\Resources\WithdrawalResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Table;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $title = 'Transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled(),

                Forms\Components\Select::make('type')
                    ->options([
                        'earning'    => 'Earning',
                        'withdrawal' => 'Withdrawal',
                    ])
                    ->disabled(),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->disabled(),

                Forms\Components\TextInput::make('reference_type')
                    ->disabled(),

                Forms\Components\TextInput::make('reference_id')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'earning',
                        'danger'  => 'withdrawal',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('eur')->sortable(),
                Tables\Columns\TextColumn::make('reference_type')->label('Reference Type'),
                Tables\Columns\TextColumn::make('reference_id')->label('Reference ID'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([])
            ->headerActions([])   // no "create" from here
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}