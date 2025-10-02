<?php

namespace App\Filament\Resources\ReservationStepResource\Pages;

use App\Filament\Resources\ReservationStepResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReservationSteps extends ListRecords
{
    protected static string $resource = ReservationStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
