<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use App\Enums\RolesEnum;

class EmployeePolicy
{
    /**
     * Determine if the user can view any employees.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }

    /**
     * Determine if the user can view the employee.
     */
    public function view(User $user, Employee $employee)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value) || $user->id === $employee->user_id;
    }

    /**
     * Determine if the user can create a new employee.
     */
    public function create(User $user)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }

    /**
     * Determine if the user can update the employee.
     */
    public function update(User $user, Employee $employee)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value) || $user->id === $employee->user_id;
    }

    /**
     * Determine if the user can delete the employee.
     */
    public function delete(User $user, Employee $employee)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }

    /**
     * Determine if the user can restore the employee.
     */
    public function restore(User $user, Employee $employee)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::Secretary->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }

    /**
     * Determine if the user can permanently delete the employee.
     */
    public function forceDelete(User $user, Employee $employee)
    {
        return $user->hasRole(RolesEnum::Director->value) || $user->hasRole(RolesEnum::RHResponsableResourceHumaine->value) || $user->hasRole(RolesEnum::SecretaryGeneral->value);
    }
}
