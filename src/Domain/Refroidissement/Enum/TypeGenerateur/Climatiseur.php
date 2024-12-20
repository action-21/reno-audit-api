<?php

namespace App\Domain\Refroidissement\Enum\TypeGenerateur;

use App\Domain\Refroidissement\Enum\TypeGenerateur;

enum Climatiseur: string
{
    case AUTRE = 'AUTRE';

    public function to(): TypeGenerateur
    {
        return TypeGenerateur::from($this->value);
    }
}
