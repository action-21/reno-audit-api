<?php

namespace App\Database\Opendata\Ecs;

use App\Database\Opendata\XMLReaderIterator;
use App\Domain\Common\Type\Id;
use App\Domain\Ecs\Enum\{CategorieGenerateur, EnergieGenerateur, LabelGenerateur, TypeChaudiere, TypeGenerateur, TypeStockage};
use App\Domain\Ecs\ValueObject\Signaletique;
use App\Domain\Ecs\ValueObject\Stockage;

final class XMLGenerateurReader extends XMLReaderIterator
{
    public function apply(): bool
    {
        return match ($this->enum_type_generateur_ecs_id()) {
            120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133 => false,
            default => true,
        };
    }

    public function id(): Id
    {
        return $this->xml()->findOneOrError('.//reference')->id();
    }

    public function generateur_mixte_id(): ?Id
    {
        return $this->xml()->findOne('.//reference_generateur_mixte')?->id();
    }

    public function generateur_mixte_exists(): ?bool
    {
        if (null === $id = $this->generateur_mixte_id()) {
            return null;
        }
        foreach ($this->xml()->etat_initial()->read_chauffage()->read_generateurs() as $item) {
            if ($item->id()->compare($id) || $item->generateur_mixte_id()?->compare($id) ?? false) {
                return true;
            }
        }
        return false;
    }

    public function reseau_chaleur_id(): ?Id
    {
        return $this->xml()->findOne('.//identifiant_reseau_chaleur')?->id();
    }

    public function description(): string
    {
        return $this->xml()->findOne('.//description')?->strval() ?? 'Générateur non décrit';
    }

    public function signaletique(): Signaletique
    {
        return new Signaletique(
            type_chaudiere: $this->type_chaudiere(),
            label: $this->label(),
            presence_ventouse: $this->presence_ventouse(),
            pn: $this->pn_saisi(),
            rpn: $this->rpn_saisi(),
            qp0: $this->qp0_saisi(),
            pveilleuse: $this->pveilleuse_saisi(),
            cop: $this->cop_saisi(),
        );
    }

    public function categorie(): CategorieGenerateur
    {
        return CategorieGenerateur::determine(type: $this->type(), energie: $this->energie());
    }

    public function type_chaudiere(): ?TypeChaudiere
    {
        return match ($this->categorie()) {
            CategorieGenerateur::CHAUDIERE_BOIS,
            CategorieGenerateur::CHAUDIERE_ELECTRIQUE,
            CategorieGenerateur::CHAUDIERE_STANDARD,
            CategorieGenerateur::CHAUDIERE_BASSE_TEMPERATURE,
            CategorieGenerateur::CHAUDIERE_CONDENSATION => match (true) {
                ($this->pn() < 18) => TypeChaudiere::CHAUDIERE_MURALE,
                ($this->pn() >= 18) => TypeChaudiere::CHAUDIERE_SOL,
                default => null,
            },
            default => null,
        };
    }

    public function type(): TypeGenerateur
    {
        return TypeGenerateur::from_enum_type_generateur_ecs_id($this->xml()->findOneOrError('.//enum_type_generateur_ecs_id')->intval());
    }

    public function energie(): EnergieGenerateur
    {
        return EnergieGenerateur::from_enum_type_energie_id($this->xml()->findOneOrError('.//enum_type_energie_id')->intval());
    }

    public function annee_installation(): ?int
    {
        return match ($this->enum_type_generateur_ecs_id()) {
            35 => 1969,
            36 => 1975,
            15, 22, 29, 85 => 1977,
            63, 110 => 1979,
            37, 45, 92, 46, 54, 93, 101 => 1980,
            58, 64, 105, 111 => 1989,
            38, 47, 94 => 1990,
            16, 23, 30, 86 => 1994,
            48, 51, 55, 59, 61, 65, 95, 98, 102, 106, 108, 112 => 2000,
            17, 24, 31, 87 => 2003,
            1, 4, 7, 10 => 2009,
            13, 115 => 2011,
            18, 25, 32, 88 => 2012,
            2, 5, 8, 11 => 2014,
            39, 41, 43, 49, 52, 56, 66, 96, 99, 103, 113 => 2015,
            19, 26, 89 => 2017,
            20, 27, 33, 90 => 2019,
            3, 6, 9, 12, 14, 21, 28, 34, 40, 42, 44, 50, 53, 57, 60, 62, 67, 91, 97, 100, 104, 107, 109, 114, 116 => $this->xml()->annee_etablissement(),
            default => null,
        };
    }

    public function label(): ?LabelGenerateur
    {
        return LabelGenerateur::from_enum_type_generateur_ecs_id($this->enum_type_generateur_ecs_id());
    }

    public function stockage_integre(): bool
    {
        return $this->enum_type_stockage_ecs_id() === 3;
    }

    public function stockage_independant(): bool
    {
        return $this->enum_type_stockage_ecs_id() === 2;
    }

    public function stockage(): ?Stockage
    {
        return $this->stockage_independant() && $this->volume_stockage() ? new Stockage(
            position_volume_chauffe: $this->position_volume_chauffe_stockage() ?? $this->position_volume_chauffe(),
            volume_stockage: $this->volume_stockage(),
        ) : null;
    }

    public function position_volume_chauffe(): bool
    {
        return $this->xml()->findOneOrError('.//position_volume_chauffe')->boolval();
    }

    public function position_volume_chauffe_stockage(): ?bool
    {
        return $this->xml()->findOne('.//position_volume_chauffe_stockage')?->boolval();
    }

    public function volume_stockage(): float
    {
        return $this->xml()->findOneOrError('.//volume_stockage')->floatval();
    }

    public function presence_ventouse(): ?bool
    {
        return $this->xml()->findOne('.//presence_ventouse')?->boolval();
    }

    public function pn_saisi(): ?float
    {
        return match ($this->enum_methode_saisie_carac_sys_id()) {
            2, 3, 4, 5, 6 => $this->pn(),
            default => null,
        };
    }

    public function rpn_saisi(): ?float
    {
        return match ($this->enum_methode_saisie_carac_sys_id()) {
            2, 3, 4, 5, 6 => $this->rpn(),
            default => null,
        };
    }

    public function qp0_saisi(): ?float
    {
        return match ($this->enum_methode_saisie_carac_sys_id()) {
            2, 3, 4, 5, 6 => $this->qp0(),
            default => null,
        };
    }

    public function pveilleuse_saisi(): ?float
    {
        return match ($this->enum_methode_saisie_carac_sys_id()) {
            2, 3, 4, 5, 6 => $this->pveilleuse(),
            default => null,
        };
    }

    public function cop_saisi(): ?float
    {
        return match ($this->enum_methode_saisie_carac_sys_id()) {
            2, 3, 4, 5, 6 => $this->cop(),
            default => null,
        };
    }

    public function enum_type_generateur_ecs_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_type_generateur_ecs_id')->intval();
    }

    public function enum_usage_generateur_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_usage_generateur_id')->intval();
    }

    public function enum_type_energie_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_type_energie_id')->intval();
    }

    public function enum_methode_saisie_carac_sys_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_methode_saisie_carac_sys_id')->intval();
    }

    public function enum_periode_installation_ecs_thermo_id(): ?int
    {
        return $this->xml()->findOne('.//enum_periode_installation_ecs_thermo_id')?->intval();
    }

    public function enum_type_stockage_ecs_id(): int
    {
        return $this->xml()->findOneOrError('.//enum_type_stockage_ecs_id')->intval();
    }

    // Données intermédiaires

    public function cop(): ?float
    {
        return $this->xml()->findOne('.//cop')?->floatval();
    }

    public function pn(): ?float
    {
        return $this->xml()->findOne('.//pn')?->floatval();
    }

    public function qp0(): ?float
    {
        return $this->xml()->findOne('.//qp0')?->floatval();
    }

    public function pveilleuse(): ?float
    {
        return $this->xml()->findOne('.//pveilleuse')?->floatval();
    }

    public function rpn(): ?float
    {
        $value = $this->xml()->findOne('.//rpn')?->floatval();
        $value = $value && $value <= 2 ? $value * 100 : $value;
        return $value;
    }

    public function rpint(): ?float
    {
        $value = $this->xml()->findOne('.//rpint')?->floatval();
        $value = $value && $value <= 2 ? $value * 100 : $value;
        return $value;
    }

    public function rendement_generation(): ?float
    {
        return $this->xml()->findOne('.//rendement_generation')?->floatval();
    }

    public function rendement_generation_stockage(): ?float
    {
        return $this->xml()->findOne('.//rendement_generation_stockage')?->floatval();
    }

    public function rendement_stockage(): ?float
    {
        return $this->xml()->findOne('.//rendement_stockage')?->floatval();
    }
}
