<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Utilisateurs Totals', User::count())
                ->description('Nombre total d\'utilisateurs')
                ->color('success')
                ->icon('heroicon-o-users'),

            Stat::make('Projects', Project::count())
                ->description('Projects')
                ->color('danger'),

            Stat::make('Tasks', Task::count())
                ->description('Tasks')
                ->color('warning'),
        ];
    }

    private function calculateUserGrowth()
    {
        $lastMonthCount = User::whereDate('created_at', '>=', now()->subMonth())->count();
        $currentMonthCount = User::whereDate('created_at', '>=', now()->startOfMonth())->count();

        if ($lastMonthCount == 0) {
            return 0;
        }

        return round((($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100, 2);
    }
}
