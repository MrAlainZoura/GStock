<?php

namespace App\Enum;

enum AbonnementType: String
{
    case Standard = 'Standard';
    case Pro = 'Pro';
    case ProPlus = 'Pro+';
    case Prenium = 'Prenium';

    public static function all(): array
{
    return [
        self::Standard,
        self::Pro,
        self::ProPlus,
        self::Prenium,
    ];
}

}

