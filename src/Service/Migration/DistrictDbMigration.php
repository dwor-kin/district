<?php
namespace App\Service\Migration;

use App\Entity\City;
use App\Entity\District;
use App\Model\CityDistrict;
use App\Repository\CityRepository;
use App\Repository\DistrictRepository;

class DistrictDbMigration
{
    /** @var CityRepository */
    private $cityRepository;

    /** @var DistrictRepository */
    private $districtRepository;

    /**
     * @param CityRepository $cityRepository
     * @param DistrictRepository $districtRepository
     */
    public function __construct(CityRepository $cityRepository, DistrictRepository $districtRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->districtRepository = $districtRepository;
    }

    /**
     * @param array $cities
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function migrateFromTheCity(array $cities): void
    {
        foreach ($cities as $cityName) {
            $city = $this->cityRepository->findOneBy(['cityName' => $cityName]);

            if (!$city instanceof City) {
                throw new \Exception ('Invalid city name');
            }

            $districtParser = ParserFactory::getParser($city->getCityName());
            $cityDistrictCollection = $districtParser->buildDistrictData();

            if (!empty($cityDistrictCollection)) {
                $this->removeDefaultEntities($city);

                /** @var CityDistrict $cityDistrict */
                foreach ($cityDistrictCollection as $cityDistrict) {
                    $district = new District();
                    $district->setCity($city);
                    $district->setIsDefault(true);
                    $district->setArea($cityDistrict->getArea());
                    $district->setDistrictName($cityDistrict->getDistrictName());
                    $district->setPopulation(($cityDistrict->getPopulation()));

                    $this->districtRepository->create($district);
                }
            }
        }
    }

    private function removeDefaultEntities(City $city): void
    {
        $districtCollection = $this->districtRepository->findBy(["city" => $city, "isDefault" => true]);

        /** @var District $district */
        foreach ($districtCollection as $district) {
            $this->districtRepository->remove($district);
        }
    }
}