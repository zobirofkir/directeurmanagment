<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Contenu du widget --}}
        <div id="calendar" class="calendar-container">
            <div class="calendar-header">
                <div class="calendar-controls">
                    <button id="today" class="control-button">Aujourd'hui</button>
                    <div class="nav-buttons">
                        <button id="prevMonth" class="nav-button">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button id="nextMonth" class="nav-button">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    <h3 class="calendar-title" id="currentMonth">Mars 2025</h3>
                </div>
                <div class="view-options">
                    <button class="view-button active" data-view="month">Mois</button>
                    <button class="view-button" data-view="week">Semaine</button>
                    <button class="view-button" data-view="day">Jour</button>
                </div>
            </div>

            <div class="calendar-grid">
                <table class="calendar-table">
                    <thead>
                        <tr>
                            <th class="calendar-header-cell">Dim</th>
                            <th class="calendar-header-cell">Lun</th>
                            <th class="calendar-header-cell">Mar</th>
                            <th class="calendar-header-cell">Mer</th>
                            <th class="calendar-header-cell">Jeu</th>
                            <th class="calendar-header-cell">Ven</th>
                            <th class="calendar-header-cell">Sam</th>
                        </tr>
                    </thead>
                    <tbody id="calendarBody">
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
                                        <td class="calendar-cell"></td>
                                    @elseif ($currentDay <= $daysInMonth)
                                        <td class="calendar-cell calendar-day">
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
                                                    <h1>{{ Str::limit($event->title, 20) }}</h1>
                                                    <p>{{ Str::limit($event->start_time, 11) }}</p>
                                                </div>
                                            @endforeach

                                            @foreach ($documents as $document)
                                                <div class="badge document-badge">
                                                    {{ Str::limit($document->title, 20) }}
                                                </div>
                                            @endforeach
                                        </td>
                                        @php $currentDay++; @endphp
                                    @else
                                        <td class="calendar-cell"></td>
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
            background: var(--cal-bg);
            border-radius: 0.75rem;
            box-shadow: var(--cal-shadow);
            transition: all 0.3s ease;
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            border-bottom: 1px solid var(--cal-border);
        }

        .calendar-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .control-button {
            padding: 0.5rem 1rem;
            background-color: var(--cal-button-bg);
            color: var(--cal-button-text);
            border: 1px solid var(--cal-button-border);
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .control-button:hover {
            background-color: var(--cal-button-hover-bg);
            border-color: var(--cal-button-hover-border);
        }

        .nav-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            background: var(--cal-button-bg);
            border: 1px solid var(--cal-button-border);
            color: var(--cal-button-text);
            transition: all 0.2s ease;
        }

        .nav-button:hover {
            background: var(--cal-button-hover-bg);
            border-color: var(--cal-button-hover-border);
        }

        .calendar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--cal-title);
            margin: 0 0.5rem;
        }

        .view-options {
            display: flex;
            background: var(--cal-button-bg);
            border: 1px solid var(--cal-button-border);
            border-radius: 0.5rem;
            padding: 0.25rem;
        }

        .view-button {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            color: var(--cal-button-text);
            transition: all 0.2s ease;
        }

        .view-button:hover {
            background: var(--cal-button-hover-bg);
        }

        .view-button.active {
            background: var(--cal-active-bg);
            color: var(--cal-active-text);
        }

        .calendar-grid {
            padding: 1rem;
        }

        .calendar-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0.5rem;
        }

        .calendar-header-cell {
            padding: 0.75rem;
            font-weight: 500;
            color: var(--cal-header-text);
            font-size: 0.875rem;
        }

        .calendar-cell {
            height: 8rem;
            border-radius: 0.5rem;
            background: var(--cal-cell-bg);
            border: 1px solid var(--cal-border);
            vertical-align: top;
            transition: all 0.2s ease;
        }

        .calendar-cell:hover {
            background: var(--cal-cell-hover-bg);
        }

        .calendar-day {
            padding: 0.5rem;
        }

        .calendar-day span {
            font-size: 0.875rem;
            color: var(--cal-day-text);
            font-weight: 500;
        }

        .event-badge {
            margin: 0.25rem 0;
            padding: 0.5rem;
            border-radius: 0.375rem;
            background: var(--cal-event-bg);
            color: var(--cal-event-text);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid var(--cal-event-border);
        }

        .event-badge:hover {
            transform: translateY(-1px);
            box-shadow: var(--cal-event-shadow);
        }

        .document-badge {
            margin: 0.25rem 0;
            padding: 0.5rem;
            border-radius: 0.375rem;
            background: var(--cal-doc-bg);
            color: var(--cal-doc-text);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid var(--cal-doc-border);
        }

        .document-badge:hover {
            transform: translateY(-1px);
            box-shadow: var(--cal-doc-shadow);
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            :root {
                --cal-bg: #1f2937;
                --cal-border: #374151;
                --cal-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
                --cal-button-bg: #374151;
                --cal-button-text: #ffffff;
                --cal-button-border: #4b5563;
                --cal-button-hover-bg: #4b5563;
                --cal-button-hover-border: #6b7280;
                --cal-title: #ffffff;
                --cal-active-bg: #3b82f6;
                --cal-active-text: #ffffff;
                --cal-header-text: #e5e7eb;
                --cal-cell-bg: #374151;
                --cal-cell-hover-bg: #4b5563;
                --cal-day-text: #ffffff;
                --cal-event-bg: #1d4ed8;
                --cal-event-text: #ffffff;
                --cal-event-border: #2563eb;
                --cal-event-shadow: 0 4px 6px -1px rgba(29, 78, 216, 0.3);
                --cal-doc-bg: #4b5563;
                --cal-doc-text: #ffffff;
                --cal-doc-border: #6b7280;
                --cal-doc-shadow: 0 4px 6px -1px rgba(75, 85, 99, 0.3);
            }
        }

        /* Light mode */
        @media (prefers-color-scheme: light) {
            :root {
                --cal-bg: #ffffff;
                --cal-border: #e5e7eb;
                --cal-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                --cal-button-bg: #f3f4f6;
                --cal-button-text: #111827;
                --cal-button-border: #e5e7eb;
                --cal-button-hover-bg: #e5e7eb;
                --cal-button-hover-border: #d1d5db;
                --cal-title: #111827;
                --cal-active-bg: #3b82f6;
                --cal-active-text: #ffffff;
                --cal-header-text: #111827;
                --cal-cell-bg: #ffffff;
                --cal-cell-hover-bg: #f9fafb;
                --cal-day-text: #111827;
                --cal-event-bg: #dbeafe;
                --cal-event-text: #1e40af;
                --cal-event-border: #bfdbfe;
                --cal-event-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
                --cal-doc-bg: #f3f4f6;
                --cal-doc-text: #111827;
                --cal-doc-border: #e5e7eb;
                --cal-doc-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendar = document.getElementById('calendar');
            const prevButton = document.getElementById('prevMonth');
            const nextButton = document.getElementById('nextMonth');
            const currentMonthElement = document.getElementById('currentMonth');
            const viewButtons = document.querySelectorAll('.view-button');

            let currentDate = new Date(2025, 2); // Mars 2025

            function updateCalendar() {
                currentMonthElement.textContent = new Intl.DateTimeFormat('fr-FR', {
                    year: 'numeric',
                    month: 'long'
                }).format(currentDate);

                // Here you would typically make an AJAX call to fetch new data
                // and update the calendar grid
            }

            prevButton.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                updateCalendar();
            });

            nextButton.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                updateCalendar();
            });

            viewButtons.forEach(button => {
                button.addEventListener('click', () => {
                    viewButtons.forEach(b => b.classList.remove('active'));
                    button.classList.add('active');
                    // Here you would implement the view change logic
                });
            });

            // Make events draggable
            const events = document.querySelectorAll('.event-badge');
            events.forEach(event => {
                event.draggable = true;
                event.addEventListener('dragstart', (e) => {
                    e.dataTransfer.setData('text/plain', event.id);
                    event.classList.add('dragging');
                });
                event.addEventListener('dragend', () => {
                    event.classList.remove('dragging');
                });
            });

            // Handle drop zones
            const cells = document.querySelectorAll('.calendar-day');
            cells.forEach(cell => {
                cell.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    cell.classList.add('drag-over');
                });

                cell.addEventListener('dragleave', () => {
                    cell.classList.remove('drag-over');
                });

                cell.addEventListener('drop', (e) => {
                    e.preventDefault();
                    cell.classList.remove('drag-over');
                    const eventId = e.dataTransfer.getData('text/plain');
                    const event = document.getElementById(eventId);
                    if (event) {
                        cell.appendChild(event);
                        // Here you would typically update the event date in the database
                    }
                });
            });
        });
    </script>
</x-filament-widgets::widget>
