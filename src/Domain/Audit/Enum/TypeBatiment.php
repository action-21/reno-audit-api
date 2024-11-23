<?php

namespace App\Domain\Audit\Enum;

use App\Domain\Common\Enum\Enum;

enum TypeBatiment: string implements Enum
{
    case MAISON = 'MAISON';
    case IMMEUBLE = 'IMMEUBLE';

    public static function from_enum_methode_application_dpe_log_id(int $id): self
    {
        return match ($id) {
            1, 14, 18 => self::MAISON,
            2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 17, 19,
            20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33,
            34, 35, 36, 37, 38, 39, 40 => self::IMMEUBLE,
        };
    }

    public function id(): string
    {
        return $this->value;
    }

    public function lib(): string
    {
        return match ($this) {
            self::MAISON => 'Maison individuelle',
            self::IMMEUBLE => 'Immeuble'
        };
    }

    public function maison(): bool
    {
        return $this->valeur === self::MAISON;
    }

    public function immeuble(): bool
    {
        return $this->valeur === self::IMMEUBLE;
    }

    /**
     * Ratio du temps d'utilisation pour les ventilations hybrides (1 par défaut)
     */
    public function ratio_temps_utilisation_ventilation(): ?float
    {
        return match ($this->valeur) {
            self::MAISON => 0.083,
            //self::APPARTEMENT => 0.167,
            self::IMMEUBLE => 0.167,
            default => 1
        };
    }
}