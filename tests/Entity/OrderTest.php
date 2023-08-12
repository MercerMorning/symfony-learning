<?php

namespace App\Tests\Entity;

use App\Entity\Order;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private function orderDataProvider(): array
    {
        $expectedPositive = [
            'id' => 1,
            'customer' => 'customer',
            'executor' => 'executor',
            'description' => 'description',
            'status' => 1,
            'price' => 150.20,
        ];
        $positiveOrder = $this->makeOrder([
            'id' => 1,
            'customer' => $this->makeUser(['login' => 'customer']),
            'executor' => $this->makeUser(['login' => 'executor']),
            'description' => 'description',
            'status' => 1,
            'price' => 150.20,
        ]);
        return [
            'positive' => [
                $positiveOrder,
                $expectedPositive,
            ],
        ];
    }

    /**
     * @dataProvider orderDataProvider
     */
    public function testToArrayReturnsCorrectValues(Order $order, array $expected): void
    {
        $actual = $order->toArray();
        static::assertSame($expected, $actual);
    }

    private function makeUser(array $data): User
    {
        $user = new User();
        $user->setLogin($data['login']);
        return $user;
    }

    private function makeOrder(array $data): Order
    {
        $order = new Order();
        $order->setId($data['id']);
        $order->setCustomer($data['customer']);
        $order->setExecutor($data['executor']);
        $order->setDescription($data['description']);
        $order->setStatus($data['status']);
        $order->setPrice($data['price']);
        $order->setCreatedAt();
        return $order;
    }
}
