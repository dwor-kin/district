<?php
namespace App\Service;

use App\Builder\DistrictBuilder;
use App\Entity\City;
use App\Entity\District;
use App\Repository\CityRepository;
use App\Repository\DistrictRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class DistrictHandler
{
    /** @var DistrictRepository */
    private $districtRepository;

    /** @var DistrictBuilder */
    private $districtBuilder;

    /** @var CityRepository */
    private $cityRepository;

    public function __construct(
        DistrictRepository $districtRepository,
        CityRepository $cityRepository,
        DistrictBuilder $districtBuilder
    ) {
        $this->districtRepository = $districtRepository;
        $this->cityRepository = $cityRepository;
        $this->districtBuilder = $districtBuilder;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function handleRemoval($id): void
    {
        $district = $this->districtRepository->findOneBy(["id" => $id]);

        if (!$district instanceof District) {
            throw new \Exception('Wrong district number');
        }

        $this->districtRepository->remove($district);
    }

    /**
     * @param FormInterface $form
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createNew(FormInterface $form): void
    {
        $cityId = $form->getData()['cityId'];
        $city = $this->cityRepository->findOneBy(['id' => $cityId]);

        if (!$city instanceof City) {
            throw new \Exception ('That city does not exist!');
        }

        $district = $this->districtBuilder->build(
            $city,
            $form->getData()['districtName'],
            (float)$form->getData()['area'],
            (int)$form->getData()['population']
        );

        $this->districtRepository->create($district);
    }

    /**
     * @param FormInterface $form
     * @throws \Exception
     */
    public function update(FormInterface $form): void
    {
        $district = $this->districtRepository->findOneBy(['id' => $form->getData()['id']]);

        if (!$district instanceof District) {
            throw new \Exception ('That district does not exist!');
        }

        $district->setDistrictName($form->getData()['districtName']);
        $district->setArea($form->getData()['area']);
        $district->setPopulation($form->getData()['population']);

        $this->districtRepository->update($district);
    }

    /**
     * @param bool $imported
     * @throws \Exception
     */
    public function purge(bool $imported): void
    {
        $districts = $this->districtRepository->findBy(['isDefault' => $imported]);

        /** @var District $district */
        foreach ($districts as $district) {
           $this->districtRepository->remove($district);
        }
    }
}