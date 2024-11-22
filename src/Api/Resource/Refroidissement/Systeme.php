<?php

namespace App\Api\Resource\Refroidissement;

use App\Domain\Common\Type\Id;
use App\Domain\Common\ValueObject\Consommation;
use App\Domain\Refroidissement\Entity\{Systeme as Entity, SystemeCollection as EntityCollection};
use App\Domain\Refroidissement\ValueObject\Performance;
use ApiPlatform\Metadata\{ApiProperty, ApiResource};

/**
 * @property Consommation[] $consommations
 */
final class Systeme
{
    public function __construct(
        #[ApiProperty(identifier: true, readable: false, writable: false)]
        public readonly Id $id,
        public readonly Id $generateur_id,
        public readonly ?float $rdim,
        public readonly ?Performance $performance,
        public readonly array $consommations,
    ) {}

    public static function from(Entity $entity): self
    {
        return new self(
            id: $entity->id(),
            generateur_id: $entity->generateur()->id(),
            rdim: $entity->rdim(),
            performance: $entity->generateur()->performance(),
            consommations: $entity->consommations()?->values() ?? [],
        );
    }

    /** @return self[] */
    public static function from_collection(EntityCollection $collection): array
    {
        return $collection->map(fn(Entity $entity) => self::from($entity))->values();
    }
}
