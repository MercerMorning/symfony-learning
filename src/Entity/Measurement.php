<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, Table};
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[
    Table(name: 'measurements'),
    Entity(repositoryClass: MeasurementRepository::class),
]
class Measurement
{

    #[
        Id,
        Column(name: 'id', type: 'integer', nullable: false, options: ['comment' => 'Measurement id']),
        GeneratedValue(strategy: "IDENTITY"),
    ]
    protected int $id;

    #[
        Column(name: 'title', type: 'string', length: 255, nullable: false),
    ]
    private string $title;

    #[
        Column(name: 'abbreviation', type: 'string', length: 255, nullable: true),
    ]
    private ?string $abbreviation = null;

    #[ORM\OneToMany(mappedBy: 'measurement', targetEntity: 'Order')]
    private array | ArrayCollection | PersistentCollection $orders;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    /**
     * @param string|null $abbreviation
     */
    public function setAbbreviation(?string $abbreviation): void
    {
        $this->abbreviation = $abbreviation;
    }

    /**
     * @return array|ArrayCollection|PersistentCollection
     */
    public function getOrders(): ArrayCollection|array|PersistentCollection
    {
        return $this->orders;
    }

    /**
     * @param array|ArrayCollection|PersistentCollection $orders
     */
    public function setOrders(ArrayCollection|array|PersistentCollection $orders): void
    {
        $this->orders = $orders;
    }
}
