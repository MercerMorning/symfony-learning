<?php

namespace App\Repository;

use App\Entity\UserProperty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProperty>
 *
 * @method UserProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProperty[]    findAll()
 * @method UserProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProperty::class);
    }

    public function save(UserProperty $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserProperty $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UserProperty[] Returns an array of UserProperty objects
//     */
    public function findByPropertyNameValue(string $name, string $value): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.name = :name')
            ->setParameter('name', $name)
            ->andWhere('u.value = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?UserProperty
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
