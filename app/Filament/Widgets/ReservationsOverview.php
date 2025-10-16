<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Product;
use App\Models\Store;
use App\Models\ReservationStep;

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
            
            Stat::make('Total Delivered Reservations', Reservation::where('last_status', 'delivered')->count())
                ->description('Reservations marked as delivered')
                ->icon('heroicon-o-truck')
                ->color('success'),
            
            Stat::make('Delivered This Week', function () {
                    // Get all ReservationSteps with status_id=6 and created_at in this week
                    $deliveredSteps = ReservationStep::where('reservation_status_id', 6)
                        ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->get();

                    // Count unique reservations delivered this week
                    $deliveredCount = $deliveredSteps->pluck('reservation_id')->unique()->count();

                    return $deliveredCount;
                })
                ->description('Reservations delivered this week')
                ->icon('heroicon-o-truck')
                ->color('success'),

            Stat::make('Average Delivery Time', function () {
                $deliveredReservations = Reservation::where('last_status', 'delivered')->pluck('id');
                $totalDelivered = $deliveredReservations->count();

                if ($totalDelivered === 0) {
                    return 'N/A';
                }

                $totalTime = 0;

                foreach ($deliveredReservations as $reservationId) {
                    $orderStep = ReservationStep::where('reservation_id', $reservationId)
                        ->where('reservation_status_id', 5) # in_transit status
                        ->orderBy('created_at', 'asc')
                        ->first();

                    $deliveryStep = ReservationStep::where('reservation_id', $reservationId)
                        ->where('reservation_status_id', 6) # delivered status
                        ->orderBy('created_at', 'asc')
                        ->first();

                    if ($orderStep && $deliveryStep) {
                        $diff = $orderStep->created_at->diffInDays($deliveryStep->created_at);
                        $totalTime += $diff;
                    }
                }

                $average = $totalTime / $totalDelivered;
                return number_format($average, 0) . ' days';
            })
            ->description('Avg. time from order to delivery')
            ->icon('heroicon-o-clock')
            ->color('warning'),

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

            // Products Section
            //Stat::make('Products', '')->description('---')->color('gray'),
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
