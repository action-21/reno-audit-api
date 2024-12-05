<?php

namespace App\Domain\Ecs\ValueObject\Signaletique;

use App\Domain\Ecs\Enum\{EnergieGenerateur, TypeGenerateur, TypeChaudiere};
use App\Domain\Ecs\ValueObject\Signaletique;

final class Chaudiere extends Signaletique
{
    public static function create(
        TypeGenerateur\Chaudiere $type,
        EnergieGenerateur\Chaudiere $energie,
        int $volume_stockage,
        bool $position_volume_chauffe,
        bool $generateur_collectif,
        TypeChaudiere $type_chaudiere,
        ?bool $presence_ventouse,
        ?float $pn,
        ?float $rpn,
        ?float $qp0,
        ?float $pveilleuse,
    ): static {
        return new self(
            type: $type->to(),
            energie: $energie->to(),
            volume_stockage: $volume_stockage,
            position_volume_chauffe: $position_volume_chauffe,
            generateur_collectif: $generateur_collectif,
            type_chaudiere: $type_chaudiere,
            presence_ventouse: $presence_ventouse,
            pn: $pn,
            rpn: $rpn,
            qp0: $qp0,
            pveilleuse: $pveilleuse,
        );
    }
}
