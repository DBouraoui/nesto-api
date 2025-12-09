<?php

namespace App\Enum;

enum UserTypeEnum: string
{
    case AGENCY = 'agency';
    case FREELANCE = 'freelance';
    case NEGOCIATOR = 'negociator';
    case SECRETAIRE = 'secretaire';
    case MANDATORY = 'mandatory';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
