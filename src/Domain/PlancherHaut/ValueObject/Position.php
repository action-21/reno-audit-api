<?php

namespace App\Domain\PlancherHaut\ValueObject;

use App\Domain\Common\Type\Id;
use App\Domain\PlancherHaut\Enum\Mitoyennete;
use Webmozart\Assert\Assert;

final class Position
{
    public function __construct(
        public readonly Mitoyennete $mitoyennete,
        public readonly ?Id $local_non_chauffe_id = null,
        public readonly ?float $orientation = null,
    ) {}

    public static function create(Mitoyennete $mitoyennete, ?float $orientation,): self
    {
        Assert::greaterThanEq($orientation, 0);
        Assert::lessThan($orientation, 360);
        Assert::notEq($mitoyennete, Mitoyennete::LOCAL_NON_CHAUFFE);

        return new self(mitoyennete: $mitoyennete, orientation: $orientation);
    }

    public static function create_liaison_local_non_chauffe(Id $local_non_chauffe_id, ?float $orientation): self
    {
        Assert::greaterThanEq($orientation, 0);
        Assert::lessThan($orientation, 360);

        return new self(
            local_non_chauffe_id: $local_non_chauffe_id,
            mitoyennete: Mitoyennete::LOCAL_NON_CHAUFFE,
            orientation: $orientation,
        );
    }
}
