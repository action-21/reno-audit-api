<?php

namespace App\Domain\Lnc\Factory;

use App\Domain\Common\Type\Id;
use App\Domain\Lnc\Entity\Baie;
use App\Domain\Lnc\Enum\TypeBaie;
use App\Domain\Lnc\Enum\TypeBaie\TypeBaieFenetre;
use App\Domain\Lnc\Lnc;
use App\Domain\Lnc\ValueObject\{Menuiserie, Position};

final class BaieFactory
{
    private Id $id;
    private Lnc $local_non_chauffe;
    private string $description;
    private float $surface;
    private float $inclinaison;
    private Position $position;

    public function initialise(
        Id $id,
        Lnc $local_non_chauffe,
        string $description,
        float $surface,
        float $inclinaison,
        Position $position,
    ): self {
        $this->id = $id;
        $this->local_non_chauffe = $local_non_chauffe;
        $this->description = $description;
        $this->surface = $surface;
        $this->inclinaison = $inclinaison;
        $this->position = $position;
        return $this;
    }

    private function build(TypeBaie $type, ?Menuiserie $menuiserie = null,): Baie
    {
        $entity = new Baie(
            id: $this->id,
            local_non_chauffe: $this->local_non_chauffe,
            description: $this->description,
            surface: $this->surface,
            inclinaison: $this->inclinaison,
            position: $this->position,
            type: $type,
            menuiserie: $menuiserie,
        );
        $entity->controle();
        return $entity;
    }

    public function build_paroi_polycarbonate(): Baie
    {
        return $this->build(type: TypeBaie::POLYCARBONATE);
    }

    public function build_fenetre(TypeBaieFenetre $type, Menuiserie $menuiserie,): Baie
    {
        return $this->build(type: $type->to(), menuiserie: $menuiserie);
    }
}
