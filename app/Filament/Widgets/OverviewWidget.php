<?php

namespace App\Filament\Widgets;

use App\Models\Project;
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

            Stat::make('Utilisateurs Actifs', User::where('is_active', true)->count())
                ->description('Utilisateurs actifs utilisant actuellement la plateforme')
                ->color('primary')
                ->icon('heroicon-o-user-circle'),

            Stat::make('Nouveaux Utilisateurs', User::whereDate('created_at', now()->toDateString())->count())
                ->description('Utilisateurs inscrits aujourd\'hui')
                ->color('info'),

            Stat::make('Croissance des Utilisateurs', $this->calculateUserGrowth())
                ->description('Pourcentage de croissance des utilisateurs')
                ->color('warning')
                ->icon('heroicon-o-arrow-up'),

            Stat::make('Utilisateurs En Attente', User::where('status', 'pending')->count())
                ->description('Utilisateurs en attente d\'approbation')
                ->color('danger')
                ->icon('heroicon-o-clock'),

            Stat::make('Projects', Project::count())
                ->description('Projects')
                ->color('success'),
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
