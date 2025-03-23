<?php

namespace App\Filament\Resources\SizeTypeResource\Pages;

use App\Filament\Resources\SizeTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSizeType extends EditRecord
{
    protected static string $resource = SizeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
