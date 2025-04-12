<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('name')->required(),
            TextInput::make('lastname')->label('Last Name'),
            TextInput::make('email')->email()->required(),
            TextInput::make('phone')->tel(),
            TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->maxLength(255),
            Select::make('role_id')
                ->relationship('role', 'name')
                ->nullable(),

            Select::make('city_id')
                ->relationship('city', 'name')
                ->nullable(),

            Checkbox::make('is_admin')->label('Admin'),
            TextInput::make('profile_status')->nullable(),
            FileUpload::make('profile_picture')->image()->nullable(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('name')->searchable(),
            TextColumn::make('lastname')->searchable(),
            TextColumn::make('email')->searchable(),
            TextColumn::make('phone')->searchable(),
            BooleanColumn::make('is_admin')->label('Admin'),
            TextColumn::make('role.name')->label('Role'),
            TextColumn::make('city.name')->label('City'),
            ImageColumn::make('profile_picture')->label('Picture')->rounded(),
            TextColumn::make('email_verified_at')->dateTime()->label('Verified At'),
            TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
