<x-filament::page>
    <div class="space-y-4">
        <h1 class="text-2xl font-bold">{{ $event->title }}</h1>
        <p><strong>Description:</strong> {{ $event->description }}</p>
        <p><strong>Start Time:</strong> {{ $event->start_time }}</p>
        <p><strong>End Time:</strong> {{ $event->end_time }}</p>
        <p><strong>Location:</strong> {{ $event->location }}</p>
        <p><strong>Type:</strong> {{ ucfirst($event->type) }}</p>
    </div>
</x-filament::page>
