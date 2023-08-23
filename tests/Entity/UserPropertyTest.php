<?php

namespace App\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;
use App\Entity\UserProperty;
use PHPUnit\Framework\TestCase;

class UserPropertyTest extends TestCase
{
    private function userPropertyDataProvider(): array
    {
        $positiveUserProperty = $this->makeUserProperty([
            'id' => 1,
            'name' => 'name',
            'value' => 'value'
        ]);
        $expectedPositive = [
            'id' => 1,
            'name' => 'name',
            'value' => 'value'
        ];
        return [
            'positive' => [
                $positiveUserProperty,
                $expectedPositive,
            ],
        ];
    }

    /**
     * @dataProvider userPropertyDataProvider
     */
    public function testToArrayReturnsCorrectValues(UserProperty $userProperty, array $expected): void
    {
        $actual = $userProperty->toArray();
        static::assertSame($expected, $actual);
    }

    private function makeUserProperty(array $data): UserProperty
    {
        $userProperty = new UserProperty();
        $userProperty->setId($data['id']);
        $userProperty->setName($data['name']);
        $userProperty->setValue($data['value']);
        return $userProperty;
    }
}
