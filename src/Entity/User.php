<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 32, nullable: false)]
    private string $login;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'UserProperty')]
    private Collection $properties;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: 'Order')]
    private Collection $acquisitions;

    #[ORM\OneToMany(mappedBy: 'executor', targetEntity: 'Order')]
    private Collection $executions;

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

    #[ArrayShape([
        'id' => 'int|null',
        'login' => 'string',
        'properties' =>  ['propertyId' => 'int', 'name' => 'string', 'value' => 'string|null'],
    ])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
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
}
