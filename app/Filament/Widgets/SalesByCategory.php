<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\Reservation;
use App\Models\ReservationStep;

class SalesByCategory extends BarChartWidget
{
    protected static ?string $heading = 'Sales by Category';

    protected function getData(): array
    {
        // Get all reservation_ids with a ReservationStep where reservation_status_id > 4
        $reservationIds = ReservationStep::where('reservation_status_id', '>', 4)
            ->pluck('reservation_id')
            ->unique();

        // Get reservations with those IDs, eager load product and category
        $reservations = Reservation::whereIn('id', $reservationIds)
            ->with(['product.category'])
            ->get();

        // Group by category name and count
        $byCategory = $reservations->groupBy(fn($r) => $r->product->category->name ?? 'Sans catégorie')
            ->map(fn($group) => $group->count());

        return [
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => $byCategory->values()->toArray(),
                ],
            ],
            'labels' => $byCategory->keys()->toArray(),
        ];
    }
}
