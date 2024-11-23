<?php

namespace App\Database\Opendata\Chauffage;

use App\Database\Opendata\XMLReaderIterator;
use App\Domain\Chauffage\Enum\{TemperatureDistribution, TypeDistribution, TypeEmetteur};
use App\Domain\Common\Type\Id;

final class XMLEmetteurReader extends XMLReaderIterator
{
    public function apply(): bool
    {
        return null !== TypeEmetteur::from_type_emission_distribution_id($this->enum_type_emission_distribution_id());
    }

    public function id(): Id
    {
        return Id::from($this->reference());
    }

    public function reference(): string
    {
        return $this->xml()->findOneOrError('.//reference')->strval();
    }

    public function description(): string
    {
        return $this->xml()->findOne('.//description')?->strval() ?? 'Emetteur non décrit';
    }

    public function type_emetteur(): TypeEmetteur
    {
        return TypeEmetteur::from_type_emission_distribution_id($this->enum_type_emission_distribution_id());
    }

    public function type_distribution(): TypeDistribution
    {
        return TypeDistribution::from_type_emission_distribution_id($this->enum_type_emission_distribution_id());
    }

    public function temperature_distribution(): TemperatureDistribution
    {
        return TemperatureDistribution::from_enum_temp_distribution_ch_id($this->enum_temp_distribution_ch_id());
    }

    public function chauffage_central(): bool
    {
        return $this->enum_type_chauffage_id() === 2;
    }

    public function chauffage_divise(): bool
    {
        return $this->enum_type_chauffage_id() === 1;
    }

    public function presence_robinet_thermostatique(): bool
    {
        return match ($this->enum_type_emission_distribution_id()) {
            29, 30, 31, 36, 37, 38, 39 => true,
            24, 25, 26, 27, 28, 32, 33, 34, 35 => false,
            default => false,
        };
    }

    public function presence_regulation_centrale(): bool
    {
        return match ($this->enum_equipement_intermittence_id()) {
            2, 3 => true,
            default => false,
        };
    }

    public function regulation_centrale_minimum_temperature(): bool
    {
        return match ($this->enum_equipement_intermittence_id()) {
            3 => true,
            default => false,
        };
    }

    public function regulation_centrale_detection_presence(): bool
    {
        return match ($this->enum_equipement_intermittence_id()) {
            7 => true,
            default => false,
        };
    }

    public function presence_regulation_terminale(): bool
    {
        return match ($this->enum_type_regulation_id()) {
            1 => false,
            2 => true,
        };
    }

    public function regulation_terminale_minimum_temperature(): bool
    {
        return match ($this->enum_equipement_intermittence_id()) {
            4, 5 => true,
            default => false,
        };
    }

    public function regulation_terminale_detection_presence(): bool
    {
        return match ($this->enum_equipement_intermittence_id()) {
            5 => true,
            default => false,
        };
    }

    public function annee_installation(): ?int
    {
        return match ($this->enum_periode_installation_emetteur_id()) {
            1 => 1980,
            2 => 2000,
            3 => $this->xml()->annee_etablissement(),
            default => null,
        };
    }

    public function comptage_individuel(): ?bool
    {
        return match ($this->tv_intermittence_id()) {
            151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169 => false,
            170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188 => true,
            default => null,
        };
    }

    public function enum_type_emission_distribution_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_type_emission_distribution_id')->intval();
    }

    public function enum_equipement_intermittence_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_equipement_intermittence_id')->intval();
    }

    public function enum_type_regulation_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_type_regulation_id')->intval();
    }

    public function enum_type_chauffage_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_type_chauffage_id')->intval();
    }

    public function enum_temp_distribution_ch_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_temp_distribution_ch_id')->intval();
    }

    public function enum_lien_generateur_emetteur_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_lien_generateur_emetteur_id')->intval();
    }

    public function enum_periode_installation_emetteur_id(): ?int
    {
        return $this->xml()->findOne('.//enum_periode_installation_emetteur_id')?->intval();
    }

    public function tv_intermittence_id(): int
    {
        return $this->xml()->findOneOrError('.//tv_intermittence_id')->intval();
    }

    public function surface_chauffee(): float
    {
        return $this->xml()->findOneOrError('.//surface_chauffee')->floatval();
    }

    public function reseau_distribution_isole(): ?bool
    {
        return $this->xml()->findOne('.//reseau_distribution_isole')?->boolval();
    }

    // Données intermédiaires

    public function i0(): float
    {
        return $this->xml()->findOneOrError('.//i0')->floatval();
    }

    public function rendement_emission(): float
    {
        return $this->xml()->findOneOrError('.//rendement_emission')->floatval();
    }

    public function rendement_distribution(): float
    {
        return $this->xml()->findOneOrError('.//rendement_distribution')->floatval();
    }

    public function rendement_regulation(): float
    {
        return $this->xml()->findOneOrError('.//rendement_regulation')->floatval();
    }
}