<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Product;
use App\Models\Store;
use App\Models\ReservationStep;
use App\Models\Transaction;
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

            /*Stat::make('Sales by Category', function () {
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

                return $byCategory->map(function($count, $category) {
                    return "$category: $count";
                })->implode(' | ');
            })
            //->description('Reservations with status > 4 by category')
            ->icon('heroicon-o-list-bullet')
            ->color('info'),*/

            Stat::make('Total Revenue', function () {
                // Get all reservation_ids with a ReservationStep where reservation_status_id > 4
                $reservationIds = ReservationStep::where('reservation_status_id', '>', 4)
                    ->pluck('reservation_id')
                    ->unique();

                // Sum the price of those reservations
                $totalRevenue = Reservation::whereIn('id', $reservationIds)->sum('price');

                return number_format($totalRevenue, 2) . ' €';
            })
            ->description('Sum of (reservation->price)')
            ->icon('heroicon-o-currency-euro')
            ->color('info'),

            Stat::make('Total PMH Relay Commission', function () {
                $sum = Transaction::where('type', 'pmh_relay_commission')->sum('amount');
                return number_format($sum, 2) . ' €';
            })
            ->description('Sum of all PMH Relay Commission transactions')
            ->icon('heroicon-o-banknotes')
            ->color('warning'),

            Stat::make('Total PMH City Commission', function () {
                $sum = Transaction::where('type', 'pmh_city_commission')->sum('amount');
                return number_format($sum, 2) . ' €';
            })
            ->description('Sum of all PMH City Commission transactions')
            ->icon('heroicon-o-banknotes')
            ->color('warning'),

            Stat::make('Sellers with Active Products', function () {
                // Get seller IDs (role_id 2 or 3)
                $sellerIds = User::whereIn('role_id', [2, 3])->pluck('id');

                // Count sellers who have at least one active product
                $count = User::whereIn('id', $sellerIds)
                    ->whereHas('products', function ($query) {
                        $query->where('is_active', true);
                    })
                    ->count();

                return $count;
            })
            ->description('Sellers with at least one active product')
            ->icon('heroicon-o-user')
            ->color('success'),

            Stat::make('Sellers with Sales Last Month', function () {
                // Get reservations with a ReservationStep where status_id > 4 and created_at last month
                $reservationIds = ReservationStep::where('reservation_status_id', '>', 4)
                    ->whereYear('created_at', now()->subMonth()->year)
                    ->whereMonth('created_at', now()->subMonth()->month)
                    ->pluck('reservation_id')
                    ->unique();

                // Get seller IDs from those reservations (role_id 2 or 3)
                $sellerIds = Reservation::whereIn('id', $reservationIds)
                    ->whereHas('seller', function ($query) {
                        $query->whereIn('role_id', [2, 3]);
                    })
                    ->pluck('seller_id')
                    ->unique();

                return $sellerIds->count();
            })
            ->description('Sellers with at least one sale last month')
            ->icon('heroicon-o-user')
            ->color('success'),

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
