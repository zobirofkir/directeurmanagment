<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class Calendar extends Component
{
    public function render()
    {
        $events = Event::where('user_id', Auth::id())->get(['id', 'title', 'start_time as start', 'end_time as end', 'type']);
        return view('livewire.calendar', compact('events'));
    }
}
