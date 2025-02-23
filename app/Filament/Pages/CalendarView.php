<?php

namespace App\Filament\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\Page;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class CalendarView extends Page
{
    protected static string $resource = EventResource::class; // ✅ تحديد المورد المرتبط بالصفحة
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.calendar-view';

    public array $events = [];

    public function mount()
    {
        $this->events = Event::where('user_id', Auth::id())
            ->get(['id', 'title', 'start_time as start', 'end_time as end', 'type'])
            ->toArray();
    }
}

