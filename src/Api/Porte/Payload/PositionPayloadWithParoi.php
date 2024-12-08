<?php

namespace App\Api\Porte\Payload;

use App\Domain\Common\Type\Id;
use App\Domain\Porte\ValueObject\Position;
use Symfony\Component\Validator\Constraints as Assert;

final class PositionPayloadWithParoi
{
    public function __construct(
        #[Assert\Uuid]
        public string $paroi_id,
    ) {}

    public function to(): Position
    {
        return Position::create_liaison_paroi(
            paroi_id: Id::from($this->paroi_id),
        );
    }
}
