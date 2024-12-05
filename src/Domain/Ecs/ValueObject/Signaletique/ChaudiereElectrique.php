<?php

namespace App\Domain\Ecs\ValueObject\Signaletique;

use App\Domain\Ecs\Enum\{EnergieGenerateur, TypeChaudiere, TypeGenerateur};
use App\Domain\Ecs\ValueObject\Signaletique;

final class ChaudiereElectrique extends Signaletique
{
    public static function create(
        TypeGenerateur\Chaudiere $type,
        int $volume_stockage,
        bool $position_volume_chauffe,
        bool $generateur_collectif,
        TypeChaudiere $type_chaudiere,
        ?float $pn,
    ): static {
        $value = new static(
            type: $type->to(),
            energie: EnergieGenerateur::ELECTRICITE,
            volume_stockage: $volume_stockage,
            position_volume_chauffe: $position_volume_chauffe,
            generateur_collectif: $generateur_collectif,
            type_chaudiere: $type_chaudiere,
            pn: $pn,
        );
        $value->controle();
        return $value;
    }
}
