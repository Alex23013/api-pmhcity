<?php

namespace App\Filament\Resources\StatusProductResource\Pages;

use App\Filament\Resources\StatusProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatusProducts extends ListRecords
{
    protected static string $resource = StatusProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
