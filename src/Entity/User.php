<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as JMS;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[JMS\Type('int')]
    #[JMS\Groups(['user-id-list'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 32, nullable: false)]
    #[JMS\Groups(['main-user-info'])]
    private string $login;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'UserProperty')]
    private Collection $properties;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: 'Order')]
    private Collection $acquisitions;

    #[ORM\OneToMany(mappedBy: 'executor', targetEntity: 'Order')]
    private Collection $executions;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'update')]
    private DateTime $updatedAt;

    #[ORM\Column(type: 'string', length: 120, nullable: false)]
    #[JMS\Exclude]
    private string $password;

    #[ORM\Column(type: 'json', length: 1024, nullable: false)]
    private array $roles = [];

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_VIEW';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


    #[ArrayShape([
        'id' => 'int|null',
        'login' => 'string',
        'password' => 'string',
        'roles' => 'string[]',
        'properties' =>  ['propertyId' => 'int', 'name' => 'string', 'value' => 'string|null'],
    ])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'password' => $this->password,
            'roles' => $this->getRoles(),
            'login' => $this->login,
            'properties' => array_map(
                static fn(UserProperty $userProperty) => [
                    'propertyId' => $userProperty->getId(),
                    'name' => $userProperty->getName(),
                    'value' => $userProperty->getValue(),
                ],
                $this->properties->toArray()
            ),
        ];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function getUsername(): string
    {
        return $this->login;
    }

    public function getProperties(): array
    {
        return $this->properties->toArray();
    }

    public function getAcquisitions(): array
    {
        return $this->acquisitions->toArray();
    }

    public function getExecutions(): array
    {
        return $this->executions->toArray();
    }
}
