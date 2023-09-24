<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @template T of object
 * @template-extends EntityRepository<T>
 */
class MeasurementRepository extends EntityRepository
{
    public function getList(): ?array
    {
        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.isShow = true')
            ->addOrderBy('m.createdAt', 'DESC')
            ->addOrderBy('m.id', 'DESC');

        return $qb->getQuery()->execute();
    }
}
