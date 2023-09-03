<?php

namespace App\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;
use App\Entity\Skill;
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
    public function testToArrayReturnsCorrectValues(Skill $userProperty, array $expected): void
    {
        $actual = $userProperty->toArray();
        static::assertSame($expected, $actual);
    }

    private function makeUserProperty(array $data): Skill
    {
        $userProperty = new Skill();
        $userProperty->setId($data['id']);
        $userProperty->setName($data['name']);
        $userProperty->setValue($data['value']);
        return $userProperty;
    }
}
