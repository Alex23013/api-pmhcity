<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Product;
use App\Models\Store;

class ReservationsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //Stat::make('Reservations', '')->description('---')->color('gray'),
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

            // Users Section
            //Stat::make('Users', '')->description('---')->color('gray'),
            Stat::make('Users This Week', User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count())
                ->description('Registered this week')
                ->icon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Users This Month', User::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count())
                ->description('Registered this month')
                ->icon('heroicon-o-user-plus'),
                
            Stat::make('Total Users', User::count())
                ->description('All-time')
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Total Active Products', Product::where('is_active', true)->count())
                ->description('Currently active')
                ->icon('heroicon-o-cube')
                ->color('info'),
            
            Stat::make('Total Verified Stores', Store::where('is_verified', true)->count())
                ->description('Currently verified')
                ->icon('heroicon-o-cube')
                ->color('info'),
        ];
    }
}
