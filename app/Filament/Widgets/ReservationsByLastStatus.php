<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\Reservation;

class ReservationsByLastStatus extends BarChartWidget
{
    protected static ?string $heading = 'Reservations by Last Status';

    protected function getData(): array
    {
        // Get all unique last_status values
        $statuses = Reservation::select('last_status')->distinct()->pluck('last_status');

        // Get counts for each status
        $counts = $statuses->mapWithKeys(function ($status) {
            return [$status => Reservation::where('last_status', $status)->count()];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Reservations',
                    'data' => $counts->values()->toArray(),
                ],
            ],
            'labels' => $counts->keys()->toArray(),
        ];
    }
}