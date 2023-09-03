<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserPropertyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`category`')]
#[ORM\Entity()]
class Category
{
    #[
        ORM\Id,
        ORM\Column(name: 'id', type: 'bigint', unique: true),
        ORM\GeneratedValue(strategy: 'IDENTITY')
    ]
    private ?int $id = null;

    #[ORM\Column(name: 'slug', type: 'string', length: 255, unique: true, nullable: false)]
    private string $slug;

    #[ORM\Column(name: 'title', type: 'string', length: 255, nullable: false)]
    private string $title;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}