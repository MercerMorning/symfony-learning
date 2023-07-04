<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\UserProperty;
use App\Repository\UserPropertyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserPropertyManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveUserProperty(int $userId, string $name, string $value): ?int
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->find($userId);
        $userProperty = new UserProperty();
        $userProperty->setUser($user);
        $userProperty->setName($name);
        $userProperty->setValue($value);
        $this->entityManager->persist($userProperty);
        $this->entityManager->flush();
        return $userProperty->getId();
    }

    public function updateUserProperty(int $userPropertyId, string $name, string $value): bool
    {
        /** @var UserPropertyRepository $userPropertyRepository */
        $userPropertyRepository = $this->entityManager->getRepository(UserProperty::class);
        /** @var UserProperty $userProperty */
        $userProperty = $userPropertyRepository->find($userPropertyId);
        if ($userProperty === null) {
            return false;
        }
        $userProperty->setName($name);
        $userProperty->setValue($name);
        $this->entityManager->flush();

        return true;
    }

    public function deleteUserProperty(UserProperty $userProperty): bool
    {
        $this->entityManager->remove($userProperty);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return User[]
     */
    public function getUserProperties(int $page, int $perPage): array
    {
        /** @var UserRepository $userRepository */
        $userPropertyRepository = $this->entityManager->getRepository(UserProperty::class);

        return $userPropertyRepository->getUserProperties($page, $perPage);
    }
}