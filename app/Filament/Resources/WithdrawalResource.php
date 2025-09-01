<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalResource\Pages;
use App\Filament\Resources\WithdrawalResource\RelationManagers;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

   protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Withdrawals';
    protected static ?string $pluralModelLabel = 'Withdrawals';
    protected static ?string $navigationGroup = 'Finance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('€')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid'     => 'Paid',
                    ])
                    ->required(),

                Forms\Components\Select::make('method')
                    ->options([
                        'bank' => 'Bank Transfer',
                        'paypal'        => 'PayPal',
                        'crypto'        => 'Crypto',
                        'stripe'       => 'Stripe',
                        'manual'       => 'Manual',
                        'other'        => 'Other',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('iban')
                    ->label('IBAN')
                    ->maxLength(34),

                Forms\Components\TextInput::make('operation_code')
                    ->label('Operation Code')
                    ->maxLength(255),

                Forms\Components\Textarea::make('notes')
                    ->rows(3),

                Forms\Components\DateTimePicker::make('requested_at')
                    ->label('Requested At'),

                Forms\Components\DateTimePicker::make('processed_at')
                    ->label('Processed At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('amount')->money('eur')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'approved',
                        'danger'  => 'rejected',
                        'success' => 'paid',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')->sortable(),
                Tables\Columns\TextColumn::make('requested_at')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('processed_at')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid'     => 'Paid',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWithdrawals::route('/'),
            'create' => Pages\CreateWithdrawal::route('/create'),
            'edit'   => Pages\EditWithdrawal::route('/{record}/edit'),
        ];
    }
}
