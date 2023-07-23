<?php

namespace App\Controller\Api\CreateUser\v3\Output;

use App\DTO\Traits\SafeLoadFieldsTrait;

class UserIsCreatedDTO
{
    use SafeLoadFieldsTrait;

    public int $id;

    public string $login;

    public int $age;

    public bool $isActive;

    public function getSafeFields(): array
    {
        return ['id', 'login'];
    }
}