<?php

namespace App\Domain\Eclairage\Data;

use App\Domain\Common\Enum\ZoneClimatique;

interface NheclRepository
{
    public function search_by(ZoneClimatique $zone_climatique): NheclCollection;
}
