<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\User;
use App\Models\Role;

class UserRoleDistribution extends BarChartWidget
{
    protected static ?string $heading = 'User Role Distribution';

    protected function getData(): array
    {
        $roles = Role::pluck('name', 'id');
        $counts = User::selectRaw('role_id, COUNT(*) as total')
            ->groupBy('role_id')
            ->pluck('total', 'role_id');

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $roles->keys()->map(fn($id) => $counts[$id] ?? 0)->toArray(),
                ],
            ],
            'labels' => $roles->values()->toArray(),
        ];
    }
}