<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Enums\RolesEnum;
use App\Models\User;
use App\Notifications\NewDocumentNotification;
use App\Filament\Resources\DocumentResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function afterCreate(): void
    {
        // Get the created document
        $document = $this->record;

        // Get all directors and secretaries
        $users = User::role([
            RolesEnum::Director->value,
            RolesEnum::Secretary->value,
            RolesEnum::SecretaryGeneral->value
        ])->get();

        // Send notification to each user
        foreach ($users as $user) {
            $user->notify(new NewDocumentNotification($document));
        }

        // Show success notification in the UI
        Notification::make()
            ->title('Document créé avec succès')
            ->body('Les notifications ont été envoyées aux utilisateurs concernés.')
            ->success()
            ->send();

        // Emit event for calendar refresh
        $this->dispatch('document-created');
    }
}
