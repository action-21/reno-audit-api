<?php

namespace App\Domain\Ecs\ValueObject\Signaletique;

use App\Domain\Ecs\Enum\{EnergieGenerateur, TypeGenerateur};
use App\Domain\Ecs\ValueObject\Signaletique;

final class ChauffeEauThermodynamique extends Signaletique
{
    public static function create(
        TypeGenerateur\ChauffeEauThermodynamique $type,
        int $volume_stockage,
        bool $position_volume_chauffe,
        bool $generateur_collectif,
        ?float $pn,
        ?float $cop,
    ): static {
        $value = new static(
            type: $type->to(),
            energie: EnergieGenerateur::ELECTRICITE,
            volume_stockage: $volume_stockage,
            position_volume_chauffe: $position_volume_chauffe,
            generateur_collectif: $generateur_collectif,
            pn: $pn,
            cop: $cop,
        );
        $value->controle();
        return $value;
    }
}