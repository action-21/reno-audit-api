<?php

namespace App\Api\Resource\Ecs;

use App\Domain\Common\Type\Id;
use App\Domain\Common\ValueObject\Consommation;
use App\Domain\Ecs\Entity\{Installation as Entity, InstallationCollection as EntityCollection};
use App\Domain\Ecs\ValueObject\Solaire;
use ApiPlatform\Metadata\{ApiProperty, ApiResource};

final class Installation
{
    public function __construct(
        #[ApiProperty(identifier: true, readable: false, writable: false)]
        public readonly Id $id,
        public readonly string $description,
        public readonly float $surface,
        public readonly ?Solaire $solaire,
        /** @var Systeme[] */
        public readonly array $systemes,
        /** @var Consommation[] */
        public readonly array $consommations,
    ) {}

    public static function from(Entity $entity): self
    {
        return new self(
            id: $entity->id(),
            description: $entity->description(),
            surface: $entity->surface(),
            solaire: $entity->solaire(),
            systemes: Systeme::from_collection($entity->systemes()),
            consommations: $entity->systemes()->consommations()?->values() ?? [],
        );
    }

    /** @return self[] */
    public static function from_collection(EntityCollection $collection): array
    {
        return $collection->map(fn(Entity $entity) => self::from($entity))->values();
    }
}
