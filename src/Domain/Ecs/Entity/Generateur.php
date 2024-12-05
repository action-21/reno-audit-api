<?php

namespace App\Domain\Ecs\Entity;

use App\Domain\Common\Type\Id;
use App\Domain\Ecs\Ecs;
use App\Domain\Ecs\Enum\{CategorieGenerateur, UsageEcs};
use App\Domain\Ecs\Service\{MoteurPerformance, MoteurPerte};
use App\Domain\Ecs\ValueObject\{Performance, PerteCollection, Signaletique};
use App\Domain\Simulation\Simulation;
use Webmozart\Assert\Assert;

final class Generateur
{
    private ?Performance $performance = null;
    private ?PerteCollection $pertes_generation = null;
    private ?PerteCollection $pertes_stockage = null;

    public function __construct(
        private readonly Id $id,
        private readonly Ecs $ecs,
        private string $description,
        private ?Id $generateur_mixte_id,
        private ?Id $reseau_chaleur_id,
        private ?int $annee_installation,
        private Signaletique $signaletique,
    ) {}

    public function controle(): void
    {
        Assert::lessThanEq($this->annee_installation, (int) date('Y'));
        Assert::greaterThanEq($this->annee_installation, $this->ecs->annee_construction_batiment());
    }

    public function reinitialise(): void
    {
        $this->performance = null;
        $this->pertes_generation = null;
        $this->pertes_stockage = null;
    }

    public function calcule_performance(MoteurPerformance $moteur, Simulation $simulation): self
    {
        $this->performance = $moteur->calcule_performance($this, $simulation);
        return $this;
    }

    public function calcule_pertes(MoteurPerte $moteur, Simulation $simulation): self
    {
        $this->pertes_generation = $moteur->calcule_pertes_generation($this, $simulation);
        $this->pertes_stockage = $moteur->calcule_pertes_stockage_generateur($this, $simulation);
        return $this;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function ecs(): Ecs
    {
        return $this->ecs;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function signaletique(): Signaletique
    {
        return $this->signaletique;
    }

    public function usage(): UsageEcs
    {
        return $this->generateur_mixte_id ? UsageEcs::CHAUFFAGE_ECS : UsageEcs::ECS;
    }

    public function annee_installation(): ?int
    {
        return $this->annee_installation;
    }

    public function reseau_chaleur_id(): ?Id
    {
        return $this->reseau_chaleur_id;
    }

    public function reference_reseau_chaleur(Id $reseau_chaleur_id): self
    {
        if ($this->signaletique->categorie() === CategorieGenerateur::RESEAU_CHALEUR) {
            $this->reseau_chaleur_id = $reseau_chaleur_id;
            $this->reinitialise();
        }
        return $this;
    }

    public function dereference_reseau_chaleur(): self
    {
        $this->reseau_chaleur_id = null;
        $this->reinitialise();
        return $this;
    }

    public function generateur_mixte_id(): ?Id
    {
        return $this->generateur_mixte_id;
    }

    public function reference_generateur_mixte(Id $generateur_mixte_id): self
    {
        if (false === \in_array($this->signaletique->categorie(), [
            CategorieGenerateur::ACCUMULATEUR,
            CategorieGenerateur::CHAUFFE_EAU_ELECTRIQUE,
            CategorieGenerateur::CHAUFFE_EAU_INSTANTANE,
            CategorieGenerateur::CHAUFFE_EAU_THERMODYNAMIQUE,
        ])) $this->generateur_mixte_id = $generateur_mixte_id;

        $this->reinitialise();
        return $this;
    }

    public function dereference_generateur_mixte(): self
    {
        $this->generateur_mixte_id = null;
        $this->reinitialise();
        return $this;
    }

    public function performance(): ?Performance
    {
        return $this->performance;
    }

    public function pertes_generation(): ?PerteCollection
    {
        return $this->pertes_generation;
    }

    public function pertes_stockage(): ?PerteCollection
    {
        return $this->pertes_stockage;
    }
}
