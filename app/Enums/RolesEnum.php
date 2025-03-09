<?php

namespace App\Enums;

enum RolesEnum: string
{
    case Director = 'directeur';
    case Staff = 'personnel';
    case Professor = 'professeur';
    case Secretary = 'secrétaire';
    case SecretaryGeneral = 'secrétaire generale';
    case DirecteurAdjoint = 'directeur adjoint';
    case RHResponsableResourceHumaine = 'RH Responsable Resource Humaine';

    public function label(): string
    {
        return match ($this) {
            static::Director => 'Directeurs',
            static::Staff => 'Personnel',
            static::Professor => 'Professeurs',
            static::Secretary => 'Secrétaires',
            static::SecretaryGeneral => 'secrétaire generale',
            static::DirecteurAdjoint => 'directeur adjoint',
            static::RHResponsableResourceHumaine => 'RH Responsable Resource Humaine',
        };
    }
}
