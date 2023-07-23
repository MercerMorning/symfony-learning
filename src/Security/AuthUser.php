<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser implements UserInterface
{
    private int $id;
    /** @var string[] */
    private array $roles;

    public function __construct(array $credentials)
    {
        $this->id = $credentials['id'];
        $this->roles = array_unique(array_merge($credentials['roles'] ?? [], ['ROLE_USER']));
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }

    public function eraseCredentials(): void
    {
    }
}