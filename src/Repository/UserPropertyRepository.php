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

    /**
     * @return UserProperty[]
     */
    public function getUserProperties(int $page, int $perPage): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't')
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

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
}
