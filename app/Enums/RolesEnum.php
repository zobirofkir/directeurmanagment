<?php

namespace App\Enums;

enum RolesEnum: string
{
    case Director = 'director';
    case Staff = 'staff';
    case Professor = 'professor';

    public function label(): string
    {
        return match ($this) {
            static::Director => 'Directors',
            static::Staff => 'Staff',
            static::Professor => 'Professors',
        };
    }
}
