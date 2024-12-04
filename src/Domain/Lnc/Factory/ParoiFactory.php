<?php

namespace App\Domain\Lnc\Factory;

use App\Domain\Common\Type\Id;
use App\Domain\Lnc\Entity\Paroi;
use App\Domain\Lnc\Enum\EtatIsolation;
use App\Domain\Lnc\Lnc;
use App\Domain\Lnc\ValueObject\Position;
use Webmozart\Assert\Assert;

final class ParoiFactory
{
    public function build(
        Id $id,
        Lnc $local_non_chauffe,
        string $description,
        Position $position,
        float $surface,
        EtatIsolation $etat_isolation,
    ): Paroi {
        Assert::greaterThan($surface, 0);
        Assert::notNull($position->mitoyennete);
        Assert::null($position->paroi);

        return new Paroi(
            id: $id,
            local_non_chauffe: $local_non_chauffe,
            description: $description,
            position: $position,
            surface: $surface,
            etat_isolation: $etat_isolation,
        );
    }
}
