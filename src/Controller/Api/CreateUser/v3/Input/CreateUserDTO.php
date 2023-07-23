<?php

namespace App\Controller\Api\CreateUser\v3\Input;

use App\DTO\Traits\SafeLoadFieldsTrait;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDTO
{
    use SafeLoadFieldsTrait;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: 32)]
    public string $login;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: 32)]
    public string $password;

    #[Assert\NotBlank]
    #[Assert\Type('array')]
    public array $roles;


    public function getSafeFields(): array
    {
        return ['login', 'password', 'roles'];
    }
}