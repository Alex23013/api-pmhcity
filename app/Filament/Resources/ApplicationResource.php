<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Applicant Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('lastname')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Store Information')
                    ->schema([
                        Forms\Components\TextInput::make('store_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('store_location')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('store_url')
                            ->url()
                            ->nullable()
                            ->maxLength(255),
                        Forms\Components\MultiSelect::make('category')
                            ->label('Categories')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->helperText('Select one or more categories')
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? implode(',', $state) : $state)
                            ->formatStateUsing(fn ($state) => $state ? explode(',', $state) : [])
                            ->nullable(),
                        Forms\Components\Textarea::make('store_description')
                            ->nullable()
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('Admin')
                    ->schema([
                        Forms\Components\Textarea::make('admin_notes')
                            ->rows(3)
                            ->nullable(),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'new' => 'New',
                                'contacted' => 'Contacted',
                                'in_progress' => 'In Progress',
                                'onboarded' => 'Onboarded',
                                'rejected' => 'Rejected',
                                'completed' => 'Completed',
                            ])
                            ->default('new')
                            ->native(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('lastname')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('store_name')->sortable()->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'new',
                        'info' => 'contacted',
                        'warning' => 'in_progress',
                        'success' => 'onboarded',
                        'danger' => 'rejected',
                        'primary' => 'completed',
                    ])
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('category_names')
                    ->label('Categories')
                    ->getStateUsing(fn ($record) => $record->category_names)
                    ->colors(['primary'])
                    ->separator(',')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'in_progress' => 'In Progress',
                        'onboarded' => 'Onboarded',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
