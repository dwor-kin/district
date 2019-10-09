<?php
namespace App\Model;

class CityDistrict
{
    /** @var string */
    private $cityName;

    /** @var string */
    private $districtName;

    /** @var float */
    private $area;

    /** @var int */
    private $population;

    /**
     * CityDistrict constructor.
     * @param string $cityName
     * @param string $districtName
     * @param float $area
     * @param int $population
     */
    public function __construct(string $cityName, string $districtName, float $area, int $population)
    {
        $this->cityName = $cityName;
        $this->districtName = $districtName;
        $this->area = $area;
        $this->population = $population;
    }

    /**
     * @param string $cityName
     * @param string $districtName
     * @param float $area
     * @param int $population
     * @return CityDistrict
     */
    static public function build(string $cityName, string $districtName, float $area, int $population): CityDistrict
    {
        return new self($cityName, $districtName, $area, $population);
    }

    /**
     * @return string
     */
    public function getCityName(): string
    {
        return $this->cityName;
    }

    /**
     * @param string $cityName
     */
    public function setCityName(string $cityName): void
    {
        $this->cityName = $cityName;
    }

    /**
     * @return string
     */
    public function getDistrictName(): string
    {
        return $this->districtName;
    }

    /**
     * @param string $districtName
     */
    public function setDistrictName(string $districtName): void
    {
        $this->districtName = $districtName;
    }

    /**
     * @return float
     */
    public function getArea(): float
    {
        return $this->area;
    }

    /**
     * @param float $area
     */
    public function setArea(float $area): void
    {
        $this->area = $area;
    }

    /**
     * @return int
     */
    public function getPopulation(): int
    {
        return $this->population;
    }

    /**
     * @param int $population
     */
    public function setPopulation(int $population): void
    {
        $this->population = $population;
    }
}