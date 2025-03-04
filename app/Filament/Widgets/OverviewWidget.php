<?php

namespace App\Filament\Widgets;

use App\Enums\RolesEnum;
use App\Models\Document;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class OverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [

            Stat::make('Chat ✨', 'Chat 🗨️')
                ->description('Chat 📲')
                ->color('success')
                ->icon('heroicon-o-cog')
                ->extraAttributes(['style' => 'text-align: center;'])
                ->url(url('admin/chats')),

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

        // Check if the user can view the 'Documents' stat
        if ($this->canViewDocuments()) {
            $stats[] = Stat::make('Documents', Document::count())
                ->description('Documents')
                ->color('success');
        }

        return $stats;
    }

    // This method checks if the logged-in user has the correct roles for the 'Documents' stat
    private function canViewDocuments(): bool
    {
        $directorRole = Role::firstOrCreate(['name' => RolesEnum::Director->value]);
        $secretaryRole = Role::firstOrCreate(['name' => RolesEnum::Secretary->value]);
        $secretaryGenerale = Role::firstOrCreate(['name' => RolesEnum::SecretaryGeneral->value]);

        return Auth::user()->hasRole($directorRole) || Auth::user()->hasRole($secretaryRole) || Auth::user()->hasRole($secretaryGenerale);
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
