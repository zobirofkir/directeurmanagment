<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RolesEnum;
use App\Models\Document;

class DocumentPolicy
{
    /**
     * Determine if the user can view any documents.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }

    /**
     * Determine if the user can view the document.
     */
    public function view(User $user, Document $document)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value) || $user->id === $document->user_id;
    }

    /**
     * Determine if the user can create a new document.
     */
    public function create(User $user)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }

    /**
     * Determine if the user can update the document.
     */
    public function update(User $user, Document $document)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value) || $user->id === $document->user_id;
    }

    /**
     * Determine if the user can delete the document.
     */
    public function delete(User $user, Document $document)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }

    /**
     * Determine if the user can restore the document.
     */
    public function restore(User $user, Document $document)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }

    /**
     * Determine if the user can permanently delete the document.
     */
    public function forceDelete(User $user, Document $document)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }
}
