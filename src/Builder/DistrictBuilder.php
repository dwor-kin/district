<?php
namespace App\Builder;

use App\Entity\City;
use App\Entity\District;

class DistrictBuilder
{
    public function build(City $city, string $districtName, float $area, string $population, bool $isDefault = false): District
    {
        $district = new District();
        $district->setCity($city);
        $district->setPopulation($population);
        $district->setArea($area);
        $district->setDistrictName($districtName);
        $district->setIsDefault($isDefault);

        return $district;
    }
}