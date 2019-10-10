<?php
namespace App\Service;

use App\Entity\District;
use App\Repository\DistrictRepository;

class DistrictProvider
{
    /**
     * @var DistrictRepository
     */
    private $districtRepository;

    /**
     * BlaBla constructor.
     * @param DistrictRepository $districtRepository
     */
    public function __construct(DistrictRepository $districtRepository)
    {
        $this->districtRepository = $districtRepository;
    }

    /**
     * @param string $sortField
     * @param string $sortType
     * @param array $filter
     * @return array
     */
    public function getDistrictData(
        string $sortField,
        string $sortType,
        array $filter
    ): array {
        if (empty($filter)) {
            return $this->districtRepository->findBy(
                array(),
                array($sortField => $sortType)
            );
        } else {
            return $this->districtRepository->findByFilter($sortField, $sortType, $filter);
        }
    }

    /**
     * @param int $id
     * @return District
     * @throws \Exception
     */
    public function getSingleDistrictData(int $id): District
    {
        $district = $this->districtRepository->findOneBy(['id' => $id]);
        if (!$district instanceof District) {
            throw new \Exception ('District not found');
        }

        return $district;
    }
}