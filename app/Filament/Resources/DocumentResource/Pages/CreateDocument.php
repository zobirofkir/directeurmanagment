<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Enums\RolesEnum;
use App\Filament\Resources\DocumentResource;
use App\Mail\DocumentCreated;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function saved(): void
    {
        parent::saved();

        $document = $this->record;

        $rolesToSendMail = [
            RolesEnum::Director->value,
            RolesEnum::Secretary->value,
            RolesEnum::SecretaryGeneral->value,
        ];

        foreach ($rolesToSendMail as $role) {
            $users = User::role($role)->get();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new DocumentCreated($document)); 
            }
        }
    }
}
