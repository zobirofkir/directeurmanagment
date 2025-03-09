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
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class OverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return Cache::remember('overview_stats_' . Auth::id(), now()->addMinutes(5), function () {
            $stats = [
                $this->getChatStat(),
                $this->getUsersStat(),
                $this->getProjectsStat(),
                $this->getTasksStat(),
            ];

            if ($this->canViewDocuments()) {
                $stats[] = $this->getDocumentsStat();
            }

            return $stats;
        });
    }

    protected function getChatStat(): Stat
    {
        return Stat::make(
            label: 'Discussion',
            value: 'Communication'
        )
            ->description('Cliquez pour ouvrir la discussion')
            ->color('success')
            ->icon('heroicon-o-chat-bubble-left-right')
            ->extraAttributes([
                'class' => 'cursor-pointer',
                'wire:click' => "navigate('".url('admin/chats')."')",
            ]);
    }

    protected function getUsersStat(): Stat
    {
        $growth = $this->calculateUserGrowth();
        $userCount = User::count();

        return Stat::make(
            label: 'Total Utilisateurs',
            value: number_format($userCount)
        )
            ->description("Croissance: {$growth}%")
            ->descriptionIcon($growth > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($growth > 0 ? 'success' : 'danger')
            ->icon('heroicon-o-users');
    }

    protected function getProjectsStat(): Stat
    {
        $projectCount = Project::count();

        return Stat::make(
            label: 'Projets',
            value: number_format($projectCount)
        )
            ->description('Projets Actifs')
            ->color('danger')
            ->icon('heroicon-o-rectangle-stack');
    }

    protected function getTasksStat(): Stat
    {
        $taskCount = Task::count();

        return Stat::make(
            label: 'Tâches',
            value: number_format($taskCount)
        )
            ->description('Tâches en Attente')
            ->color('warning')
            ->icon('heroicon-o-clipboard-document-list');
    }

    protected function getDocumentsStat(): Stat
    {
        $documentCount = Document::count();

        return Stat::make(
            label: 'Documents',
            value: number_format($documentCount)
        )
            ->description('Total des Documents')
            ->color('success')
            ->icon('heroicon-o-document');
    }

    protected function canViewDocuments(): bool
    {
        $allowedRoles = [
            RolesEnum::Director->value,
            RolesEnum::Secretary->value,
            RolesEnum::SecretaryGeneral->value,
        ];

        return Auth::user()->roles()->whereIn('name', $allowedRoles)->exists();
    }

    protected function calculateUserGrowth(): float
    {
        $lastMonthCount = User::whereDate('created_at', '>=', now()->subMonth())->count();
        $currentMonthCount = User::whereDate('created_at', '>=', now()->startOfMonth())->count();

        if ($lastMonthCount === 0) {
            return 0.0;
        }

        return round((($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100, 2);
    }

    public function navigate($url)
    {
        return redirect()->to($url);
    }
}
