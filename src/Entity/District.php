<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Trip
 * @ORM\Table(name="district", indexes={@ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 */
class District implements \JsonSerializable
{
    use BaseTrait;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="App\Entity\City", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="city_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $city;

    /**
     * @var bool
     * @ORM\Column(name="is_default", type="boolean", nullable=false)
     */
    private $isDefault = false;

    /**
     * @var string
     * @ORM\Column(name="district_name", type="string", length=100, nullable=false)
     */
    private $districtName;

    /**
     * @var float
     * @ORM\Column(name="area", type="float", nullable=false)
     */
    private $area;

    /**
     * @var int
     * @ORM\Column(name="population", type="integer", nullable=false)
     */
    private $population;

    /**
     * @return City
     */
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city): void
    {
        $this->city = $city;
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

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     */
    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): array
    {
        return [
            'id'             => $this->getId(),
            'cityId'         => $this->getCity()->getId(),
            'districtName'   => $this->getDistrictName(),
            'area'           => $this->getArea(),
            'population'     => $this->getPopulation()
        ];
    }

}