<?php

namespace App\Filament\Widgets\Tables;

use App\Models\User;
use Filament\Widgets\ChartWidget as WidgetsChartWidget;

class ChartWidget extends WidgetsChartWidget
{
    protected static ?string $heading = 'Croissance des utilisateurs au fil du temps';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $usersPerDay = User::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $usersPerDay->pluck('date')->toArray();
        $data = $usersPerDay->pluck('count')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Utilisateurs créés',
                    'data' => $data,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
