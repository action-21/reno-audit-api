<?php

namespace App\Domain\Chauffage\Enum;

use App\Domain\Common\Enum\Enum;

enum TypeDistribution: string implements Enum
{
    case SANS = 'SANS';
    case HYDRAULIQUE = 'HYDRAULIQUE';
    case AERAULIQUE = 'AERAULIQUE';
    case FLUIDE_FRIGORIGENE = 'FLUIDE_FRIGORIGENE';

    public static function from_type_emission_distribution_id(int $id): self
    {
        return match ($id) {
            46, 47, 48, 49, 11, 12, 13, 14, 43, 15, 16, 17, 18, 44, 24,
            25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39 => self::HYDRAULIQUE,
            42, 45 => self::FLUIDE_FRIGORIGENE,
            5 => self::AERAULIQUE,
            default => self::SANS,
        };
    }

    public function id(): string
    {
        return $this->value;
    }

    public function lib(): string
    {
        return match ($this) {
            self::SANS => 'Sans distribution',
            self::HYDRAULIQUE => 'Distribution hydraulique',
            self::AERAULIQUE => 'Distribution aéraulique',
            self::FLUIDE_FRIGORIGENE => 'Distribution par fluide frigorigène',
        };
    }
}
