<?php

namespace App\Manager;

use App\DTO\CreateOrderDTO;
use App\Entity\Measurement;
use App\Entity\Order;
use App\Entity\User;
use App\ExceptionHandler\ExceptionHandlerInterface;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\AsyncService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class MeasurementManager
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function createMeasurement(
        string $title,
        string $abbreviation,
    ): ?Measurement
    {
        $measurement = new Measurement();
        $measurement->setTitle($title);
        $measurement->setAbbreviation($abbreviation);
        $this->entityManager->persist($measurement);
        $this->entityManager->flush();
        return $measurement;
    }
}