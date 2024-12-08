<?php

namespace App\Domain\Mur\ValueObject;

use App\Domain\Common\Service\Assert;
use App\Domain\Common\Type\Id;
use App\Domain\Mur\Enum\Mitoyennete;

final class Position
{
    public function __construct(
        public readonly Mitoyennete $mitoyennete,
        public readonly float $orientation,
        public readonly ?Id $local_non_chauffe_id = null,
    ) {}

    public static function create(Mitoyennete $mitoyennete, float $orientation,): self
    {
        return new self(mitoyennete: $mitoyennete, orientation: $orientation);
    }

    public static function create_liaison_local_non_chauffe(Id $local_non_chauffe_id, float $orientation): self
    {
        return new self(
            local_non_chauffe_id: $local_non_chauffe_id,
            mitoyennete: Mitoyennete::LOCAL_NON_CHAUFFE,
            orientation: $orientation,
        );
    }

    public function controle(): void
    {
        Assert::orientation($this->orientation);

        if ($this->mitoyennete->local_non_chauffe() && $this->local_non_chauffe_id === null)
            throw new \InvalidArgumentException('Le local non chauffé est requis');
    }
}
