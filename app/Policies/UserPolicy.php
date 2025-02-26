<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RolesEnum;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }

    /**
     * Determine if the user can view the user model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function view(User $user, User $model)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value) || $user->id === $model->id;
    }

    /**
     * Determine if the user can update the user model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function update(User $user, User $model)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value) || $user->id === $model->id;
    }

    /**
     * Determine if the user can delete the user model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function delete(User $user, User $model)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value) || $user->id === $model->id;
    }

    /**
     * Determine if the user can create a new user.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value) || $user->hasRole(RolesEnum::Secretary->value);
    }
}
