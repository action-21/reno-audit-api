<?php

namespace App\Api\PlancherHaut\Payload;

use App\Domain\Common\Type\Id;
use App\Domain\PlancherHaut\Enum\Mitoyennete;
use App\Domain\PlancherHaut\ValueObject\Position;
use App\Services\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class PositionPayload
{
    public function __construct(
        public Mitoyennete $mitoyennete,
        #[AppAssert\Orientation]
        public ?float $orientation,
        #[Assert\Uuid]
        public ?string $local_non_chauffe_id,
    ) {}

    public function to(): Position
    {
        return Position::create(
            mitoyennete: $this->mitoyennete,
            orientation: $this->orientation,
            local_non_chauffe_id: $this->local_non_chauffe_id ? Id::from($this->local_non_chauffe_id) : null,
        );
    }
}
