<?php

namespace App\Filament\Resources\SizeTypeResource\Pages;

use App\Filament\Resources\SizeTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSizeTypes extends ListRecords
{
    protected static string $resource = SizeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
