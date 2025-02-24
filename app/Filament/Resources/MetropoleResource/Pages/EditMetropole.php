<?php

namespace App\Filament\Resources\MetropoleResource\Pages;

use App\Filament\Resources\MetropoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetropole extends EditRecord
{
    protected static string $resource = MetropoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
