<?php

namespace App\DTO;

use App\Entity\Order;
use App\Entity\UserProperty;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class ManageUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 32)]
        public string $login = '',

        #[Assert\NotBlank]
        #[Assert\Length(max: 32)]
        public string $password = '',

        #[Assert\Type('array')]
        public array $properties = [],

        #[Assert\Type('array')]
        public array $acquisitions = [],

        #[Assert\Type('array')]
        public array $executions = [],

        #[Assert\Type('array')]
        public array $roles = []
    ) {
    }

    public static function fromEntity(User $user): self
    {
        return new self(...[
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'roles' => $user->getRoles(),
            'properties' => array_map(
                static function (UserProperty $userProperty) {
                    return [
                        'id' => $userProperty->getId(),
                        'name' => $userProperty->getName(),
                        'value' => $userProperty->getValue(),
                    ];
                },
                $user->getProperties()
            ),
            'acquisitions' => array_map(
                static function (Order $order) {
                    return [
                        'id' => $order->getId(),
                        'description' => $order->getDescription(),
                        'price' => $order->getPrice(),
                        'status' => $order->getStatus(),
                    ];
                },
                $user->getProperties()
            ),
            'executions' => array_map(
                static function (Order $order) {
                    return [
                        'id' => $order->getId(),
                        'description' => $order->getDescription(),
                        'price' => $order->getPrice(),
                        'status' => $order->getStatus(),
                    ];
                },
                $user->getExecutions()
            ),
        ]);
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            login: $request->request->get('login') ?? $request->query->get('login'),
            password: $request->request->get('password') ?? $request->query->get('password'),
            roles: $request->request->get('roles') ?? $request->query->get('roles') ?? [],
        );
    }
}