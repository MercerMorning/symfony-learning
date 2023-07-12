<?php

namespace App\Controller\Api\CreateUser\v3;

use App\Controller\Api\CreateUser\v3\Input\CreateUserDTO;
use App\Controller\Api\CreateUser\v3\Output\UserIsCreatedDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserManager
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        UserPasswordHasherInterface $userPasswordHasher,
    )
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function saveUser(CreateUserDTO $saveUserDTO): UserIsCreatedDTO
    {
        $user = new User();
        $user->setLogin($saveUserDTO->login);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $saveUserDTO->password));
        $user->setRoles($saveUserDTO->roles);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $result = new UserIsCreatedDTO();
        $context = (new SerializationContext())->setGroups(['main-user-info', 'user-id-list']);
        $result->loadFromJsonString($this->serializer->serialize($user, 'json', $context));

        return $result;
    }
}