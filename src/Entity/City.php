<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Trip
 * @ORM\Table(name="city", indexes={@ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{
    use BaseTrait;

    /**
     * @var string
     * @ORM\Column(name="city_name", type="string", length=100, nullable=true)
     */
    private $cityName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\District", mappedBy="city", cascade={"persist", "remove"},
     *                                                          orphanRemoval=true)
     */
    private $district;

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
     * @return ArrayCollection
     */
    public function getDistrict(): ArrayCollection
    {
        return $this->district;
    }

    /**
     * @param ArrayCollection $district
     */
    public function setDistrict(ArrayCollection $district): void
    {
        $this->district = $district;
    }
}