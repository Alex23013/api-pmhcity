<?php

namespace App\Filament\Widgets;

use Filament\Widgets\PieChartWidget;
use App\Models\User;

class SellerDistribution extends PieChartWidget
{
    protected static ?string $heading = 'Distribution of Sellers';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $roles = [
            2 => 'Commercant Brand',
            3 => 'Commercant Commercial',
        ];

        $counts = collect($roles)->map(function ($label, $roleId) {
            return User::where('role_id', $roleId)->count();
        });

        return [
            'datasets' => [
                [
                    'data' => $counts->values()->toArray(),
                ],
            ],
            'labels' => $counts->keys()->map(fn($roleId) => $roles[$roleId])->toArray(),
        ];
    }
}