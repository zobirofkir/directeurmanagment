<?php

namespace App\Filament\Resources\EventResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\EventResource;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class CalendarView extends Page
{
    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.resources.events.view-event';

    public Event $event;

    public function mount()
    {
        $recordId = request()->query('record');

        if (!$recordId) {
            abort(404);
        }

        $this->event = Event::where('id', $recordId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    public function getViewData(): array
    {
        return [
            'event' => $this->event,
        ];
    }
}
