<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\Product;
use App\Models\Category;

class ProductsByCategoryDistribution extends BarChartWidget
{
    protected static ?string $heading = 'Products by Category';

    protected function getData(): array
    {
        $categories = Category::pluck('name', 'id');
        $counts = Product::selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        return [
            'datasets' => [
                [
                    'label' => 'Products',
                    'data' => $categories->keys()->map(fn($id) => $counts[$id] ?? 0)->toArray(),
                ],
            ],
            'labels' => $categories->values()->toArray(),
        ];
    }
}