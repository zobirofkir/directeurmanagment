<?php

namespace App\Filament\Widgets\Tables;

use Filament\Widgets\Widget;
use App\Models\Event;
use App\Models\Document;
use Carbon\Carbon;

class CalendarWidget extends Widget
{
    protected static string $view = 'filament.widgets.tables.calendar-widget';

    protected int|string|array $columnSpan = 'full';

    public function getData(): array
    {
        $events = Event::whereYear('start_time', 2025)
            ->whereMonth('start_time', 3)
            ->get();

        $documents = Document::whereYear('created_at', 2025)
            ->whereMonth('created_at', 3)
            ->get();

        return [
            'events' => $events,
            'documents' => $documents,
        ];
    }
}
