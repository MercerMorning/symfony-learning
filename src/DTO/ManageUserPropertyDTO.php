<?php

namespace App\DTO;

use App\Entity\UserProperty;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class ManageUserPropertyDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public User|null $user = null,

        #[Assert\NotBlank]
        #[Assert\Length(min: 3)]
        public string $name = '',

        #[Assert\NotBlank]
        #[Assert\Length(min: 3)]
        public string $value = '',
    ) {
    }

    public static function fromEntity(UserProperty $userProperty): self
    {
        return new self(...[
            'user' => $userProperty->getUser(),
            'name' => $userProperty->getName(),
            'value' => $userProperty->getValue()
        ]);
    }
}