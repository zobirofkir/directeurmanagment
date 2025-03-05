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

    // Add state properties for filtering
    public $currentDate;
    public $showEvents = true;
    public $showDocuments = true;
    public $selectedCategory = 'all';
    public $startDate = null;
    public $endDate = null;
    public $notification = null;

    public function mount()
    {
        $this->currentDate = Carbon::create(2025, 3, 1);
        $this->startDate = $this->currentDate->copy()->startOfMonth()->format('Y-m-d');
        $this->endDate = $this->currentDate->copy()->endOfMonth()->format('Y-m-d');
    }

    public function updatedStartDate($value)
    {
        if ($value) {
            $this->currentDate = Carbon::parse($value);
        }
    }

    public function updatedEndDate($value)
    {
        // Optional: Add validation if end date is before start date
        if ($value && $this->startDate && Carbon::parse($value)->isBefore(Carbon::parse($this->startDate))) {
            $this->endDate = $this->startDate;
        }
    }

    public function showAlert($message, $type = 'info')
    {
        $this->notification = [
            'message' => $message,
            'type' => $type
        ];
    }

    public function nextMonth()
    {
        $this->currentDate->addMonth();
    }

    public function previousMonth()
    {
        $this->currentDate->subMonth();
    }

    public function toggleEvents()
    {
        $this->showEvents = !$this->showEvents;
    }

    public function toggleDocuments()
    {
        $this->showDocuments = !$this->showDocuments;
    }

    public function setCategory($category)
    {
        $this->selectedCategory = $category;
    }

    public function getData(): array
    {
        $events = collect([]);
        $documents = collect([]);

        $startDate = $this->startDate ? Carbon::parse($this->startDate) : $this->currentDate->copy()->startOfMonth();
        $endDate = $this->endDate ? Carbon::parse($this->endDate) : $this->currentDate->copy()->endOfMonth();

        if ($this->showEvents) {
            $events = Event::whereBetween('start_time', [$startDate, $endDate])
                ->when($this->selectedCategory !== 'all', function ($query) {
                    return $query->where('type', $this->selectedCategory);
                })
                ->get();
        }

        if ($this->showDocuments) {
            $documents = Document::whereBetween('date', [$startDate, $endDate])
                ->where('archived', false)
                ->when($this->selectedCategory !== 'all', function ($query) {
                    return $query->where('category', $this->selectedCategory);
                })
                ->get();
        }

        return [
            'events' => $events,
            'documents' => $documents,
            'currentDate' => $this->currentDate,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
}
