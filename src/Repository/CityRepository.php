<?php
namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class CityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, City::class);
    }

    /**
     * @param City $city
     * @return City
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(City $city): City
    {
        $em = $this->getEntityManager();

        $em->beginTransaction();
        try {
            $em->persist($city);
            $em->flush();
            $em->commit();
            return $city;
        } catch (Exception $e) {
            $em->rollback();
            throw new \Exception($e);
        }
    }

    public function remove(City $city)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {
            $em->remove($city);
            $em->flush();
            $em->commit();
        } catch (Exception $e) {
            $em->rollback();
            throw new \Exception($e);
        }
    }

    /** @inheritDoc */
    public function update(City $city)
    {
        $em = $this->getEntityManager();

        $em->beginTransaction();
        try {
            $em->merge($city);
            $em->flush();
            $em->commit();
        } catch (Exception $e) {
            $em->rollback();
            throw new \Exception($e);
        }
    }
}
