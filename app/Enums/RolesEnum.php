<?php

namespace App\Enums;

enum RolesEnum: string
{
    case Director = 'directeur';
    case Staff = 'personnel';
    case Professor = 'professeur';
    case Secretary = 'secrétaire';

    public function label(): string
    {
        return match ($this) {
            static::Director => 'Directeurs',
            static::Staff => 'Personnel',
            static::Professor => 'Professeurs',
            static::Secretary => 'Secrétaires',
        };
    }
}
