<?php

namespace App\Filament\Resources\MetropoleResource\Pages;

use App\Filament\Resources\MetropoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetropoles extends ListRecords
{
    protected static string $resource = MetropoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
