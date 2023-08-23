<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    const CUSTOMERS = [
        'customer',
        'customer2'
    ];
    const EXECUTORS = [
        'executor',
        'executor2',
    ];

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'login' => 'customer',
                'password' => 'customer'
            ],
            [
                'login' => 'executor',
                'password' => 'executor'
            ],
            [
                'login' => 'customer2',
                'password' => 'customer2'
            ],
            [
                'login' => 'executor2',
                'password' => 'executor2'
            ]
        ];
        foreach ($usersData as $userData) {
            $user = new User();
            $user->setLogin($userData['login']);
            $user->setPassword($userData['password']);
            $manager->persist($user);
            $this->addReference($userData['login'], $user);
        }
        $manager->flush();
    }
}

