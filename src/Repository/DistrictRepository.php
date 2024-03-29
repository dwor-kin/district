<?php
namespace App\Repository;

use App\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

class DistrictRepository extends ServiceEntityRepository
{
    private $aliasFieldDefinitions = [
        'd' => array('districtName', 'area', 'population'),
        'c' => array('cityName')
    ];

    /**
     * @param RegistryInterface $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, District::class);
    }

    /**
     * @param District $district
     * @return District
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(District $district): District
    {
        $em = $this->getEntityManager();

        $em->beginTransaction();
        try {
            $em->persist($district);
            $em->flush();
            $em->commit();
            return $district;
        } catch (Exception $e) {
            $em->rollback();
            throw new \Exception($e);
        }
    }

    public function remove(District $district)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {
            $em->remove($district);
            $em->flush();
            $em->commit();
        } catch (Exception $e) {
            $em->rollback();
            throw new \Exception($e);
        }
    }

    /** @inheritDoc */
    public function update(District $district)
    {
        $em = $this->getEntityManager();

        $em->beginTransaction();
        try {
            $em->merge($district);
            $em->flush();
            $em->commit();
        } catch (Exception $e) {
            $em->rollback();
            throw new \Exception($e);
        }
    }

    /**
     * @param string $sortField
     * @param string $sortType
     * @param array $filters
     * @return array
     */
    public function findByFilter(string $sortField, string $sortType, array $filters): array
    {
        $qb = $this->createQueryBuilder('d');
        $qb->join('App\Entity\City', 'c', Expr\Join::WITH, 'd.city = c.id');
        $qb->where('d.id > :startIndex');
        $qb->setParameter(':startIndex', 1);

        if (!empty($filters)) {

            foreach ($filters as $fieldName => $filterValue) {
                if (!empty($filterValue)) {
                    if ($fieldName == 'area' || $fieldName == 'population') {
                        if (!empty($filterValue['min']) && !empty($filterValue['max'])) {
                            $qb->andWhere(
                                $this->findAlias($fieldName) . "." . $fieldName . " BETWEEN " . $filterValue['min'] . " AND " . $filterValue['max']
                            );
                        }
                        elseif (!empty($filterValue['min']) && empty($filterValue['max'])) {
                            $qb->andWhere(
                                $this->findAlias($fieldName) . "." . $fieldName . " > " . $filterValue['min']
                            );
                        }
                        elseif (empty($filterValue['min']) && !empty($filterValue['max'])) {
                            $qb->andWhere(
                                $this->findAlias($fieldName) . "." . $fieldName . " < " . $filterValue['max']
                            );
                        }
                    } else {
                        $qb->andWhere($this->findAlias($fieldName) ."." . $fieldName . " LIKE '%" . $filterValue . "%'");
                    }
                }
            }
        }

        $qb->addOrderBy('d.' . $sortField, $sortType);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $fieldName
     * @return string|null
     */
    private function findAlias($fieldName)
    {
        foreach ($this->aliasFieldDefinitions as $fieldAlias => $fieldDef) {
            foreach ($fieldDef as $fieldNameDef) {
                if ($fieldNameDef == $fieldName) {
                    return $fieldAlias;
                }
            }
        }

        return null;
    }
}