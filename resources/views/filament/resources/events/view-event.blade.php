<x-filament::page>
    <div class="overflow-x-auto bg-gray-50 dark:bg-gray-900 p-6 rounded-xl shadow-2xl max-w-4xl mx-auto transform transition-transform duration-300 hover:scale-105 md:block hidden">
        <table class="min-w-full table-auto bg-white dark:bg-gray-800 shadow-3xl rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gradient-to-r from-indigo-600 to-indigo-500 dark:text-white text-black">
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Événement</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Heure de début</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Heure de fin</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">URL de la réunion</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Type</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr class="transition-colors duration-200">
                    <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $event->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $event->description }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $event->start_time }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $event->end_time }}</td>
                    <td class="px-6 py-4 text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200 whitespace-nowrap">
                        <a href="{{ $event->location }}" target="_blank" rel="noopener noreferrer" class="underline">
                            Rejoindre la réunion
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 capitalize">{{ $event->type }}</td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="sm:hidden text-gray-800 dark:text-gray-200">
        <div class="flex justify-between py-2">
            <strong>Événement:</strong> {{ $event->title }}
        </div>
        <div class="flex justify-between py-2">
            <strong>Description:</strong> {{ $event->description }}
        </div>
        <div class="flex justify-between py-2">
            <strong>Début:</strong> {{ $event->start_time }}
        </div>
        <div class="flex justify-between py-2">
            <strong>Fin:</strong> {{ $event->end_time }}
        </div>
        <div class="flex justify-between py-2">
            <strong>Réunion:</strong>
            <a href="{{ $event->location }}" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300">
                Rejoindre la réunion
            </a>
        </div>
        <div class="flex justify-between py-2">
            <strong>Type:</strong> {{ $event->type }}
        </div>
    </div>

</x-filament::page>
