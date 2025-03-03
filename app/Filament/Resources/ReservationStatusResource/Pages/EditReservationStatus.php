<?php

namespace App\Filament\Resources\ReservationStatusResource\Pages;

use App\Filament\Resources\ReservationStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReservationStatus extends EditRecord
{
    protected static string $resource = ReservationStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
