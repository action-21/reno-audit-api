<?php

namespace App\Database\Opendata\Enveloppe;

use App\Database\Opendata\{XMLElement, XMLReader};
use App\Database\Opendata\Baie\XMLBaieReader;
use App\Database\Opendata\Mur\XMLMurReader;
use App\Database\Opendata\PlancherBas\XMLPlancherBasReader;
use App\Database\Opendata\PlancherHaut\XMLPlancherHautReader;
use App\Database\Opendata\PontThermique\XMLPontThermiqueReader;
use App\Database\Opendata\Porte\XMLPorteReader;
use App\Domain\Enveloppe\Enum\Exposition;

final class XMLEnveloppeReader extends XMLReader
{
    /** @return XMLMurReader[] */
    public function read_murs(): array
    {
        return \array_map(
            fn(XMLElement $xml): XMLMurReader => XMLMurReader::from($xml),
            $this->xml()->findMany('.//mur_collection//mur')
        );
    }

    /** @return XMLPlancherBasReader[] */
    public function read_planchers_bas(): array
    {
        return \array_map(
            fn(XMLElement $xml): XMLPlancherBasReader => XMLPlancherBasReader::from($xml),
            $this->xml()->findMany('.//plancher_bas_collection//plancher_bas')
        );
    }

    /** @return XMLPlancherHautReader[] */
    public function read_planchers_hauts(): array
    {
        return \array_map(
            fn(XMLElement $xml): XMLPlancherHautReader => XMLPlancherHautReader::from($xml),
            $this->xml()->findMany('.//plancher_haut_collection//plancher_haut')
        );
    }

    /** @return XMLBaieReader[] */
    public function read_baies(): array
    {
        return \array_map(
            fn(XMLElement $xml): XMLBaieReader => XMLBaieReader::from($xml),
            $this->xml()->findMany('.//baie_vitree_collection//baie_vitree')
        );
    }

    /** @return XMLPorteReader[] */
    public function read_portes(): array
    {
        return \array_map(
            fn(XMLElement $xml): XMLPorteReader => XMLPorteReader::from($xml),
            $this->xml()->findMany('.//porte_collection//porte')
        );
    }

    /** @return XMLPontThermiqueReader[] */
    public function read_ponts_thermiques(): array
    {
        return \array_map(
            fn(XMLElement $xml): XMLPontThermiqueReader => XMLPontThermiqueReader::from($xml),
            $this->xml()->findMany('.//pont_thermique_collection//pont_thermique')
        );
    }

    public function plusieurs_facade_exposee(): bool
    {
        return $this->xml()->findOneOrError('//plusieurs_facade_exposee')->getValue();
    }

    public function q4pa_conv(): ?float
    {
        return $this->xml()->findOne('//q4pa_conv_saisi')?->floatval();
    }

    public function exposition(): Exposition
    {
        return $this->plusieurs_facade_exposee() ? Exposition::EXPOSITION_MULTIPLE : Exposition::EXPOSITION_SIMPLE;
    }

    // Données intermédiaires

    public function hvent(): float
    {
        return $this->xml()->findOneOrError('//deperdition/hvent')->floatval();
    }

    public function hperm(): float
    {
        return $this->xml()->findOneOrError('//deperdition/hperm')->floatval();
    }

    public function deperdition_renouvellement_air(): float
    {
        return $this->xml()->findOneOrError('//deperdition/deperdition_renouvellement_air')->floatval();
    }

    public function deperdition_mur(): float
    {
        return $this->xml()->findOneOrError('//deperdition/deperdition_mur')->floatval();
    }

    public function deperdition_plancher_bas(): float
    {
        return $this->xml()->findOneOrError('//deperdition/deperdition_plancher_bas')->floatval();
    }

    public function deperdition_plancher_haut(): float
    {
        return $this->xml()->findOneOrError('//deperdition/deperdition_plancher_haut')->floatval();
    }

    public function deperdition_baie(): float
    {
        return $this->xml()->findOneOrError('//deperdition/deperdition_baie_vitree')->floatval();
    }

    public function deperdition_porte(): float
    {
        return $this->xml()->findOneOrError('//deperdition/deperdition_porte')->floatval();
    }

    public function deperdition_pont_thermique(): float
    {
        return $this->xml()->findOneOrError('//deperdition/deperdition_pont_thermique')->floatval();
    }

    public function deperdition_enveloppe(): float
    {
        return $this->xml()->findOneOrError('//deperdition/deperdition_enveloppe')->floatval();
    }

    public function surface_sud_equivalente(): float
    {
        return $this->xml()->findOneOrError('//apport_et_besoin/surface_sud_equivalente')->floatval();
    }

    public function apport_solaire_fr(): float
    {
        return $this->xml()->findOneOrError('//apport_et_besoin/apport_solaire_fr')->floatval();
    }

    public function apport_interne_fr(): float
    {
        return $this->xml()->findOneOrError('//apport_et_besoin/apport_interne_fr')->floatval();
    }

    public function apport_solaire_ch(): float
    {
        return $this->xml()->findOneOrError('//apport_et_besoin/apport_solaire_ch')->floatval();
    }

    public function apport_interne_ch(): float
    {
        return $this->xml()->findOneOrError('//apport_et_besoin/apport_interne_ch')->floatval();
    }

    public function fraction_apport_gratuit_ch(bool $scenario_depensier = false): float
    {
        return $scenario_depensier
            ? $this->xml()->findOneOrError('//apport_et_besoin/fraction_apport_gratuit_depensier_ch')->floatval()
            : $this->xml()->findOneOrError('//apport_et_besoin/fraction_apport_gratuit_ch')->floatval();
    }
}
