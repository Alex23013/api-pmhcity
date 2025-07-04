<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Reservations Today', Reservation::whereDate('created_at', today())->count())
                ->description('New today')
                ->icon('heroicon-o-calendar')
                ->color('success'),

            Stat::make('This Week', Reservation::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count())
                ->description('So far this week')
                ->icon('heroicon-o-clock'),

            Stat::make('Total Reservations', Reservation::count())
                ->description('All-time')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('primary'),
        ];
    }
}
