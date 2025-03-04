<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Contenu du widget --}}
        <div id="calendar" class="calendar-container">
            <h3 class="calendar-title">Mars 2025</h3>
            <div class="calendar-table">
                <table>
                    <thead>
                        <tr>
                            <th>Dim</th>
                            <th>Lun</th>
                            <th>Mar</th>
                            <th>Mer</th>
                            <th>Jeu</th>
                            <th>Ven</th>
                            <th>Sam</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Génération dynamique des jours --}}
                        @php
                            $firstDayOfMonth = \Carbon\Carbon::create(2025, 3, 1)->startOfMonth()->dayOfWeek;
                            $daysInMonth = \Carbon\Carbon::create(2025, 3, 1)->daysInMonth;
                            $currentDay = 1;
                        @endphp

                        @for ($row = 0; $row < 5; $row++)
                            <tr>
                                @for ($col = 0; $col < 7; $col++)
                                    @if ($row == 0 && $col < $firstDayOfMonth)
                                        <td></td>
                                    @elseif ($currentDay <= $daysInMonth)
                                        <td class="calendar-day">
                                            <span>{{ $currentDay }}</span>

                                            {{-- Affichage des événements et documents pour ce jour --}}
                                            @php
                                                $events = App\Models\Event::whereDate('created_at', \Carbon\Carbon::create(2025, 3, $currentDay)->format('Y-m-d'))->get();
                                                $documents = App\Models\Document::whereDate('created_at', \Carbon\Carbon::create(2025, 3, $currentDay)->format('Y-m-d'))
                                                                                ->where('archived', false)
                                                                                ->get();
                                            @endphp

                                            @foreach ($events as $event)
                                                <div class="badge event-badge">
                                                    <h1>{{ Str::limit($event->title, 10) }}</h1>
                                                    <p>{{ Str::limit($event->start_time, 10) }}</p>
                                                </div>
                                            @endforeach

                                            @foreach ($documents as $document)
                                                <div class="badge document-badge">
                                                    {{ Str::limit($document->title, 10) }}
                                                </div>
                                            @endforeach
                                        </td>
                                        @php $currentDay++; @endphp
                                    @else
                                        <td></td>
                                    @endif
                                @endfor
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </x-filament::section>

    <style>
        .calendar-container {
            max-width: 100%;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .calendar-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: var(--calendar-title);
        }
        .calendar-table {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            padding: 10px;
            border: 1px solid var(--calendar-border);
            height: 60px;
            vertical-align: top;
        }
        th {
            font-weight: bold;
        }
        .calendar-day {
            position: relative;
        }
        .badge {
            position: absolute;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 12px;
            color: white;
        }
        .event-badge {
            background-color: #007bff;
            top: 5px;
            left: 5px;
        }
        .document-badge {
            background-color: #6c757d;
            top: 5px;
            right: 5px;
        }

        /* Mode sombre */
        @media (prefers-color-scheme: dark) {
            :root {
                --calendar-bg: #1a1a1a;
                --calendar-text: #ffffff;
                --calendar-title: #ffffff;
                --calendar-border: #333333;
                --calendar-header-bg: #333333;
                --calendar-header-text: #ffffff;
            }
        }

        /* Mode clair */
        @media (prefers-color-scheme: light) {
            :root {
                --calendar-bg: #ffffff;
                --calendar-text: #000000;
                --calendar-title: #000000;
                --calendar-border: #dddddd;
                --calendar-header-bg: #f4f4f4;
                --calendar-header-text: #000000;
            }
        }

        /* Responsivité */
        @media (max-width: 768px) {
            table {
                font-size: 12px;
            }
            th, td {
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            th, td {
                padding: 6px;
            }
            .calendar-title {
                font-size: 18px;
            }
        }
    </style>
</x-filament-widgets::widget>
