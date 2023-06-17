<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\Index(columns: ['customer_id'], name: 'order__customer_id__ind')]
#[ORM\Index(columns: ['executor_id'], name: 'order__customer_id__ind')]
class Order
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'id')]
    private User $customer;

    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(name: 'executor_id', referencedColumnName: 'id')]
    private User $executor;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $description;

    #[ORM\Column(type: Types::SMALLINT, nullable: false)]
    private int $status;

    #[ORM\Column(type: Types::FLOAT, nullable: false)]
    private float $price;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCustomer(): User
    {
        return $this->customer;
    }

    public function setCustomer(User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getExecutor(): User
    {
        return $this->executor;
    }

    public function setExecutor(User $executor): static
    {
        $this->executor = $executor;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    #[ArrayShape([
        'id' => 'int|null',
        'customer' => ['id' => 'int', 'login' => 'string', 'properties' => 'array[]'],
        'executor' => ['id' => 'int', 'login' => 'string', 'properties' => 'array[]'],
        'description' => 'string',
        'status' => 'int',
        'price' => 'double'
    ])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer->toArray(),
            'executor' => $this->executor->toArray(),
            'description' => $this->description,
            'status' => $this->status,
            'price' => $this->price
        ];
    }
}
