<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Add Alert Component --}}
        <div id="calendar-alert" class="calendar-alert hidden">
            <div class="alert-content">
                <span class="alert-message"></span>
                <button class="alert-close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Add Filter Controls --}}
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="date"
                            wire:model.live="startDate"
                            class="w-40"
                            placeholder="Start Date"
                        />
                    </x-filament::input.wrapper>

                    <span class="text-gray-500">to</span>

                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="date"
                            wire:model.live="endDate"
                            class="w-40"
                            placeholder="End Date"
                        />
                    </x-filament::input.wrapper>
                </div>

                <button
                    wire:click="toggleEvents"
                    class="px-4 py-2 rounded-lg {{ $showEvents ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }}"
                >
                    {{ $showEvents ? 'Hide Events' : 'Show Events' }}
                </button>

                <button
                    wire:click="toggleDocuments"
                    class="px-4 py-2 rounded-lg {{ $showDocuments ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }}"
                >
                    {{ $showDocuments ? 'Hide Documents' : 'Show Documents' }}
                </button>

                <select
                    wire:model.live="selectedCategory"
                    class="rounded-lg border-gray-300"
                >
                    <option value="all">All Categories</option>
                    <optgroup label="Events">
                        <option value="meeting">Meetings</option>
                        <option value="deadline">Deadlines</option>
                        <option value="other">Other Events</option>
                    </optgroup>
                    <optgroup label="Documents">
                        <option value="contract">Contracts</option>
                        <option value="certificate">Certificates</option>
                        <option value="leave">Leave</option>
                        <option value="other">Other Documents</option>
                    </optgroup>
                </select>
            </div>

            <div class="flex items-center space-x-4">
                <button
                    wire:click="previousMonth"
                    class="p-2 rounded-lg hover:bg-gray-100"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <span class="text-lg font-semibold">
                    {{ $currentDate->format('F Y') }}
                </span>

                <button
                    wire:click="nextMonth"
                    class="p-2 rounded-lg hover:bg-gray-100"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

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
                        {{-- Replace the static date generation with dynamic dates from events and documents --}}
                        @php
                            $data = $this->getData();
                            $currentMonth = $data['currentDate'];
                            $firstDayOfMonth = $currentMonth->copy()->startOfMonth();
                            $firstDayOfWeek = $firstDayOfMonth->dayOfWeek;  // Get the day number (0-6)
                            $daysInMonth = $currentMonth->daysInMonth;
                            $currentDay = 1;

                            // Group events and documents by date
                            $events = $data['events']->groupBy(function($event) {
                                return $event->start_time->format('Y-m-d');
                            });

                            $documents = $data['documents']->groupBy(function($document) {
                                return $document->date->format('Y-m-d');
                            });
                        @endphp

                        @for ($row = 0; $row < 5; $row++)
                            <tr>
                                @for ($col = 0; $col < 7; $col++)
                                    @if ($row == 0 && $col < $firstDayOfWeek)
                                        <td class="calendar-cell"></td>
                                    @elseif ($currentDay <= $daysInMonth)
                                        <td class="calendar-cell calendar-day">
                                            <span>{{ $currentDay }}</span>

                                            {{-- Display events for this day --}}
                                            @php
                                                $dayDate = $currentMonth->copy()->setDay($currentDay)->format('Y-m-d');
                                                $dayEvents = $events[$dayDate] ?? collect();
                                                $dayDocuments = $documents[$dayDate] ?? collect();
                                            @endphp

                                            @foreach ($dayEvents as $event)
                                                <div class="badge event-badge" onclick="showAlert('Événement: {{ $event->title }}', 'info')">
                                                    <h1>{{ Str::limit($event->title, 20) }}</h1>
                                                    <p>{{ $event->start_time->format('H:i') }}</p>
                                                </div>
                                            @endforeach

                                            @foreach ($dayDocuments as $document)
                                                <div class="badge document-badge" onclick="showAlert('Document: {{ $document->title }}', 'info')">
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

        .calendar-alert {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
            transition: all 0.3s ease;
        }

        .calendar-alert.hidden {
            opacity: 0;
            transform: translateY(-1rem);
            pointer-events: none;
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--cal-alert-bg, #3b82f6);
            color: var(--cal-alert-text, #ffffff);
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .alert-message {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .alert-close {
            padding: 0.25rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .alert-close:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Alert types */
        .alert-info {
            --cal-alert-bg: #3b82f6;
            --cal-alert-text: #ffffff;
        }

        .alert-success {
            --cal-alert-bg: #10b981;
            --cal-alert-text: #ffffff;
        }

        .alert-warning {
            --cal-alert-bg: #f59e0b;
            --cal-alert-text: #ffffff;
        }

        .alert-error {
            --cal-alert-bg: #ef4444;
            --cal-alert-text: #ffffff;
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
                        showAlert('Événement déplacé avec succès', 'success');
                        // Here you would typically update the event date in the database
                    }
                });
            });

            // Add alert functionality
            function showAlert(message, type = 'info') {
                const alert = document.getElementById('calendar-alert');
                const alertMessage = alert.querySelector('.alert-message');

                alert.classList.remove('hidden');
                alert.querySelector('.alert-content').className = `alert-content alert-${type}`;
                alertMessage.textContent = message;

                // Auto-hide after 3 seconds
                setTimeout(() => {
                    hideAlert();
                }, 3000);
            }

            function hideAlert() {
                const alert = document.getElementById('calendar-alert');
                alert.classList.add('hidden');
            }

            // Add click handler for alert close button
            document.querySelector('.alert-close').addEventListener('click', hideAlert);
        });
    </script>
</x-filament-widgets::widget>
