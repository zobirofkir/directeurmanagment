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
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-4">
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

            <div class="flex items-center gap-4">
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
            border-radius: 1rem;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            background: var(--cal-header-bg);
            border-bottom: 1px solid var(--cal-border);
        }

        .calendar-controls {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .nav-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .control-button, .nav-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--cal-button-border);
            background: var(--cal-button-bg);
            color: var(--cal-button-text);
            cursor: pointer;
        }

        .control-button:hover, .nav-button:hover {
            background: var(--cal-button-hover-bg);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .nav-button {
            width: 2.75rem;
            height: 2.75rem;
            padding: 0;
        }

        .calendar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--cal-title);
            margin: 0 1rem;
        }

        .view-options {
            display: flex;
            background: var(--cal-view-options-bg);
            border-radius: 0.75rem;
            padding: 0.25rem;
            gap: 0.25rem;
        }

        .view-button {
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            border: none;
            background: transparent;
            color: var(--cal-button-text);
            cursor: pointer;
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
            padding: 1.5rem;
        }

        .calendar-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0.75rem;
        }

        .calendar-header-cell {
            padding: 0.75rem;
            font-weight: 600;
            color: var(--cal-header-text);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .calendar-cell {
            height: 9rem;
            border-radius: 0.75rem;
            background: var(--cal-cell-bg);
            border: 1px solid var(--cal-border);
            vertical-align: top;
            transition: all 0.2s ease;
        }

        .calendar-cell:hover {
            background: var(--cal-cell-hover-bg);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .calendar-day {
            padding: 0.75rem;
            position: relative;
        }

        .calendar-day span {
            font-size: 0.95rem;
            color: var(--cal-day-text);
            font-weight: 500;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .event-badge, .document-badge {
            margin: 0.35rem 0;
            padding: 0.625rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
        }

        .event-badge {
            background: var(--cal-event-bg);
            color: var(--cal-event-text);
            border: 1px solid var(--cal-event-border);
        }

        .document-badge {
            background: var(--cal-doc-bg);
            color: var(--cal-doc-text);
            border: 1px solid var(--cal-doc-border);
        }

        .event-badge:hover, .document-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            :root {
                --cal-bg: #1a1f2d;
                --cal-header-bg: #1f2937;
                --cal-border: #2d3748;
                --cal-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
                --cal-button-bg: #2d3748;
                --cal-button-text: #e2e8f0;
                --cal-button-border: #4a5568;
                --cal-button-hover-bg: #3a4657;
                --cal-title: #f7fafc;
                --cal-active-bg: #3b82f6;
                --cal-active-text: #ffffff;
                --cal-header-text: #a0aec0;
                --cal-cell-bg: #1f2937;
                --cal-cell-hover-bg: #2d3748;
                --cal-day-text: #e2e8f0;
                --cal-event-bg: #1e3a8a;
                --cal-event-text: #bfdbfe;
                --cal-event-border: #2563eb;
                --cal-doc-bg: #374151;
                --cal-doc-text: #e5e7eb;
                --cal-doc-border: #4b5563;
                --cal-view-options-bg: #2d3748;
            }
        }

        /* Light mode */
        @media (prefers-color-scheme: light) {
            :root {
                --cal-bg: #ffffff;
                --cal-header-bg: #f8fafc;
                --cal-border: #e2e8f0;
                --cal-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                --cal-button-bg: #f1f5f9;
                --cal-button-text: #475569;
                --cal-button-border: #e2e8f0;
                --cal-button-hover-bg: #e2e8f0;
                --cal-title: #1e293b;
                --cal-active-bg: #3b82f6;
                --cal-active-text: #ffffff;
                --cal-header-text: #64748b;
                --cal-cell-bg: #ffffff;
                --cal-cell-hover-bg: #f8fafc;
                --cal-day-text: #334155;
                --cal-event-bg: #eff6ff;
                --cal-event-text: #1e40af;
                --cal-event-border: #bfdbfe;
                --cal-doc-bg: #f8fafc;
                --cal-doc-text: #475569;
                --cal-doc-border: #e2e8f0;
                --cal-view-options-bg: #f1f5f9;
            }
        }

        /* Alert Styles */
        .calendar-alert {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 50;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 24rem;
        }

        .alert-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .alert-message {
            font-size: 0.925rem;
            font-weight: 500;
        }

        .alert-close {
            padding: 0.375rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            margin-left: 1rem;
        }

        .alert-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Alert Types */
        .alert-info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
        }

        .alert-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
        }

        .alert-error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
        }

        /* Drag and Drop Styles */
        .dragging {
            opacity: 0.5;
            cursor: move;
        }

        .drag-over {
            background: var(--cal-cell-hover-bg);
            border: 2px dashed var(--cal-active-bg);
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
