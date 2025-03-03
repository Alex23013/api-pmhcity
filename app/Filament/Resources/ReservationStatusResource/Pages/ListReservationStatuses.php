<?php

namespace App\Filament\Resources\ReservationStatusResource\Pages;

use App\Filament\Resources\ReservationStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReservationStatuses extends ListRecords
{
    protected static string $resource = ReservationStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
