<?php

namespace App\Domain\Chauffage\Enum;

use App\Domain\Chauffage\ValueObject\Regulation;
use App\Domain\Common\Enum\Enum;

enum TypeIntermittence: string implements Enum
{
    case ABSENT = 'ABSENT';
    case CENTRAL = 'CENTRAL';
    case CENTRAL_MINIMUM_TEMPERATURE = 'CENTRAL_MINIMUM_TEMPERATURE';
    case TERMINAL_DETECTION_PRESENCE = 'TERMINAL_DETECTION_PRESENCE';
    case TERMINAL_MINIMUM_TEMPERATURE = 'TERMINAL_MINIMUM_TEMPERATURE';
    case TERMINAL_MINIMUM_TEMPERATURE_DETECTION_PRESENCE = 'TERMINAL_MINIMUM_TEMPERATURE_DETECTION_PRESENCE';

    public static function determine(
        Regulation $regulation_centrale,
        Regulation $regulation_terminale,
        bool $chauffage_collectif,
    ): self {
        if ($chauffage_collectif) {
            return match (true) {
                $regulation_terminale->detection_presence => self::TERMINAL_DETECTION_PRESENCE,
                $regulation_centrale->minimum_temperature => self::CENTRAL_MINIMUM_TEMPERATURE,
                default => self::ABSENT,
            };
        }
        return match (true) {
            $regulation_terminale->minimum_temperature && $regulation_terminale->detection_presence => self::TERMINAL_MINIMUM_TEMPERATURE_DETECTION_PRESENCE,
            $regulation_terminale->minimum_temperature => self::TERMINAL_MINIMUM_TEMPERATURE,
            $regulation_centrale->minimum_temperature => self::CENTRAL_MINIMUM_TEMPERATURE,
            $regulation_centrale->presence_regulation => self::CENTRAL,
            default => self::ABSENT,
        };
    }

    public function id(): string
    {
        return $this->value;
    }

    public function lib(): string
    {
        return match ($this) {
            self::ABSENT => 'Absence d\'intermittence',
            self::CENTRAL => 'Programmation d\'intermittence centrale sans minimum de température',
            self::CENTRAL_MINIMUM_TEMPERATURE => 'Programmation d\'intermittence centrale avec minimum de température',
            self::TERMINAL_DETECTION_PRESENCE => 'Programmation d\'intermittence terminale avec détection de présence',
            self::TERMINAL_MINIMUM_TEMPERATURE => 'Programmation d\'intermittence terminale avec minimum de température',
            self::TERMINAL_MINIMUM_TEMPERATURE_DETECTION_PRESENCE => 'Programmation d\'intermittence terminale avec minimum de température et détection de présence',
        };
    }
}
