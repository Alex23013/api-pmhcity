<?php

namespace App\Filament\Resources\StatusProductResource\Pages;

use App\Filament\Resources\StatusProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatusProduct extends EditRecord
{
    protected static string $resource = StatusProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
