<?php

namespace App\Manager;

use App\DTO\ManageUserPropertyDTO;
use App\Entity\User;
use App\Entity\Skill;
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
        $userProperty = new Skill();
        $userProperty->setUser($user);
        $userProperty->setName($name);
        $userProperty->setValue($value);
        $this->entityManager->persist($userProperty);
        $this->entityManager->flush();
        return $userProperty->getId();
    }

    public function saveUserPropertyFromDTO(Skill $userProperty, ManageUserPropertyDTO $manageUserPropertyDTO): ?int
    {
        $userProperty->setUser($manageUserPropertyDTO->user);
        $userProperty->setName($manageUserPropertyDTO->name);
        $userProperty->setValue($manageUserPropertyDTO->value);
        $this->entityManager->persist($userProperty);
        $this->entityManager->flush();
        return $userProperty->getId();
    }

    public function updateUserProperty(int $userPropertyId, string $name, string $value): bool
    {
        /** @var UserPropertyRepository $userPropertyRepository */
        $userPropertyRepository = $this->entityManager->getRepository(Skill::class);
        /** @var Skill $userProperty */
        $userProperty = $userPropertyRepository->find($userPropertyId);
        if ($userProperty === null) {
            return false;
        }
        $userProperty->setName($name);
        $userProperty->setValue($name);
        $this->entityManager->flush();

        return true;
    }

    public function deleteUserProperty(Skill $userProperty): bool
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
        $userPropertyRepository = $this->entityManager->getRepository(Skill::class);

        return $userPropertyRepository->getUserProperties($page, $perPage);
    }

    public function getUserPropertyById(int $id): ?Skill
    {
        /** @var UserPropertyRepository $userPropertyRepository */
        $userPropertyRepository = $this->entityManager->getRepository(Skill::class);

        return $userPropertyRepository->find($id);
    }
}