<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RolesEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any users.
     */
    public function viewAny(?User $user): bool
    {
        if (!$user) return false;

        return $user->hasAnyRole([
            RolesEnum::Director->value,
            RolesEnum::Secretary->value,
            RolesEnum::SecretaryGeneral->value
        ]);
    }

    /**
     * Determine if the user can view the user model.
     */
    public function view(?User $user, User $model): bool
    {
        if (!$user) return false;

        return $user->hasAnyRole([
            RolesEnum::Director->value,
            RolesEnum::Secretary->value,
            RolesEnum::SecretaryGeneral->value
        ]) || $user->id === $model->id;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(?User $user): bool
    {
        if (!$user) return false;

        return $user->hasAnyRole([
            RolesEnum::Director->value,
            RolesEnum::SecretaryGeneral->value,
            RolesEnum::Secretary->value
        ]);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(?User $user, User $model): bool
    {
        if (!$user) return false;

        return $user->hasAnyRole([
            RolesEnum::Director->value,
            RolesEnum::Secretary->value,
            RolesEnum::SecretaryGeneral->value
        ]) || $user->id === $model->id;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(?User $user, User $model): bool
    {
        if (!$user) return false;

        // Prevent users from deleting themselves
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasAnyRole([
            RolesEnum::Director->value,
            RolesEnum::Secretary->value,
            RolesEnum::SecretaryGeneral->value
        ]);
    }
}
