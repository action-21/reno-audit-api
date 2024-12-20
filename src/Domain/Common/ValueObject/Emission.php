<?php

namespace App\Domain\Common\ValueObject;

use App\Domain\Common\Enum\{Mois, ScenarioUsage, Usage};
use Webmozart\Assert\Assert;

final class Emission
{
    public function __construct(
        public readonly Usage $usage,
        public readonly ScenarioUsage $scenario,
        public readonly Mois $mois,
        public readonly float $emission,
    ) {}

    public static function create(
        Usage $usage,
        ScenarioUsage $scenario,
        Mois $mois,
        float $emission
    ): self {
        Assert::greaterThanEq($emission, 0);
        return new self(
            usage: $usage,
            scenario: $scenario,
            mois: $mois,
            emission: $emission,
        );
    }
}
