<?php

namespace App\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;
use App\Entity\UserProperty;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private function userDataProvider(): array
    {
        $positiveUser = $this->makeUser([
            'id' => 1,
            'password' => 'test',
            'roles' => ['viewer'],
            'login' => 'login',
            'properties' => new ArrayCollection([
                $this->makeProperty([
                    'id' => 1,
                    'name' => 'country',
                    'value' => 'spanish'
                ])
            ])
        ]);
        $expectedPositive = [
            'id' => 1,
            'password' => 'test',
            'roles' => ['viewer', 'ROLE_VIEW'],
            'login' => 'login',
            'properties' => [
                [
                    'id' => 1,
                    'name' => 'country',
                    'value' => 'spanish'
                ]
            ]
        ];
        $withoutRolesUser = $this->makeUser([
            'id' => 1,
            'password' => 'test',
            'roles' => [],
            'login' => 'login',
            'properties' => new ArrayCollection([
                $this->makeProperty([
                    'id' => 1,
                    'name' => 'country',
                    'value' => 'spanish'
                ])
            ])
        ]);
        $expectedWithoutRoles = [
            'id' => 1,
            'password' => 'test',
            'roles' => ['ROLE_VIEW'],
            'login' => 'login',
            'properties' => [
                [
                    'id' => 1,
                    'name' => 'country',
                    'value' => 'spanish'
                ]
            ]
        ];
        $withoutPropertiesUser = $this->makeUser([
            'id' => 1,
            'password' => 'test',
            'roles' => [],
            'login' => 'login',
            'properties' => new ArrayCollection()
        ]);
        $expectedWithoutProperties = [
            'id' => 1,
            'password' => 'test',
            'roles' => ['ROLE_VIEW'],
            'login' => 'login',
            'properties' => []
        ];
        return [
            'positive' => [
                $positiveUser,
                $expectedPositive,
            ],
            'without_roles' => [
                $withoutRolesUser,
                $expectedWithoutRoles,
            ],
            'without_properties' => [
                $withoutPropertiesUser,
                $expectedWithoutProperties,
            ],
        ];
    }

    /**
     * @dataProvider userDataProvider
     */
    public function testToArrayReturnsCorrectValues(User $user, array $expected): void
    {
        $actual = $user->toArray();
        static::assertSame($expected, $actual);
    }

    private function makeUser(array $data): User
    {
        $user = new User();
        $user->setId($data['id']);
        $user->setLogin($data['login']);
        $user->setPassword($data['password']);
        $user->setRoles($data['roles']);
        $user->setProperties($data['properties']);
        return $user;
    }

    private function makeProperty(array $data): UserProperty
    {
        $userProperty = new UserProperty();
        $userProperty->setId($data['id']);
        $userProperty->setName($data['name']);
        $userProperty->setValue($data['value']);
        return $userProperty;
    }
}
