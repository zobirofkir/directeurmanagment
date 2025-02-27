<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Task;
use Filament\Infolists\Components\BadgeEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('title')
                ->label('📌 Title')
                ->weight('bold')
                ->color('primary')
                ->size('lg'),

            TextEntry::make('description')
                ->label('📝 Description')
                ->color('gray')
                ->size('sm'),

            Textentry::make('status')
                ->label('📌 Status')
                ->color(fn (string $state): string => match ($state) {
                    'To Do' => 'gray',
                    'In Progress' => 'warning',
                    'Done' => 'success',
                }),

            TextEntry::make('deadline')
                ->label('⏳ Deadline')
                ->date()
                ->color('danger')
                ->size('sm'),

            TextEntry::make('meeting_link')
                ->label('🔗 Meeting Link')
                ->url(fn(Task $record) => $record->meeting_link ? url($record->meeting_link) : null)
                ->openUrlInNewTab()
                ->hidden(fn(Task $record) => empty($record->meeting_link))
                ->color('info'),

            TextEntry::make('project.name')
                ->label('📁 Project')
                ->color('primary')
                ->weight('bold')
                ->size('lg'),
        ]);
    }
}
