<?php

namespace App\Api\Baie\Payload;

use App\Domain\Baie\Enum\Mitoyennete;
use App\Domain\Baie\ValueObject\Position;
use App\Domain\Common\Type\Id;
use App\Services\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class PositionPayload
{
    public function __construct(
        public Mitoyennete $mitoyennete,
        #[AppAssert\Orientation]
        public ?float $orientation,
        #[Assert\Uuid]
        public ?string $paroi_id,
        #[Assert\Uuid]
        public ?string $local_non_chauffe_id,
    ) {}

    public function to(): Position
    {
        return $this->paroi_id ? Position::create_liaison_paroi(paroi_id: Id::from($this->paroi_id)) : Position::create(
            mitoyennete: $this->mitoyennete,
            orientation: $this->orientation,
            local_non_chauffe_id: $this->local_non_chauffe_id ? Id::from($this->local_non_chauffe_id) : null,
        );
    }
}