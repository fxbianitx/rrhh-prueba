<?php

namespace App\Enums;

enum ContractType: string
{
    case PLAZO_FIJO = 'Plazo fijo';
    case TEMPORAL = 'Temporal';
    case INDEFINIDO = 'Indefinido';
    case LOCACION_SERVICIOS = 'Locacion de servicios';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
