<?php

namespace App\Domain\Chauffage\Data;

use App\Domain\Chauffage\Enum\{LabelGenerateur, TypeEmission, TypeGenerateur};

interface RrRepository
{
    public function find_by(
        TypeEmission $type_emission,
        TypeGenerateur $type_generateur,
        ?LabelGenerateur $label_generateur,
        ?bool $reseau_collectif,
        ?bool $presence_robinet_thermostatique,
        ?bool $presence_regulation_terminale,
    ): ?Rr;
}
