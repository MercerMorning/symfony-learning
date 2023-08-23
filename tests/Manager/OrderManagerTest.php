<?php

namespace App\Tests\Manager;

use App\DataFixtures\OrderFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Order;
use App\Entity\User;
use App\Manager\OrderManager;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\AsyncService;
use App\Tests\FixturedTestCase;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class OrderManagerTest extends FixturedTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->addFixture(new UserFixtures());
        $this->addFixture(new OrderFixtures());
        $this->executeFixtures();
    }

    public function orderSuccessSaveDataProvider()
    {
        return [
            'positive' => [
                'customerLogin' => UserFixtures::CUSTOMERS[0],
                'executorLogin' => UserFixtures::EXECUTORS[0],
                'description' => 'test',
                'status' => 1,
                'price' => 150.20,
            ],
        ];
    }

    public function orderFailSaveDataProvider()
    {
        return [
            'with_non_exist_customer_and_executor' => [
                'customerId' => 0,
                'executorId' => 0,
                'description' => 'test',
                'status' => 1,
                'price' => 150.20,
            ],
        ];
    }

    public function orderSuccessUpdateDataProvider()
    {
        return [
            'positive' => [
                'customerLogin' => UserFixtures::CUSTOMERS[1],
                'executorLogin' => UserFixtures::EXECUTORS[1],
                'description' => 'update',
                'status' => 3,
                'price' => 10.20,
            ],
        ];
    }

    public function orderFailUpdateDataProvider()
    {
        return [
            'with_non_exist_customer_and_executor' => [
                'customerLogin' => 0,
                'executorLogin' => 0,
                'description' => 'update',
                'status' => 3,
                'price' => 10.20,
            ],
        ];
    }

    public function orderSuccessDeleteDataProvider()
    {
        return [
            'positive' => [
                'customerLogin' => UserFixtures::CUSTOMERS[0],
                'executorLogin' => UserFixtures::EXECUTORS[0],
            ],
        ];
    }

    /**
     * @return void
     * @dataProvider orderSuccessSaveDataProvider
     */
    public function testSuccessSaveOrder(string $customerLogin, string $executorLogin, string $description, int $status, float $price): void
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrineManager()->getRepository(User::class);
        $customer = $userRepository->findOneBy(['login' => $customerLogin]);
        $executor = $userRepository->findOneBy(['login' => $executorLogin]);
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->getDoctrineManager()->getRepository(Order::class);
        $orderManager = new OrderManager(
            $this->getDoctrineManager(),
            $this->createMock(TagAwareCacheInterface::class),
            $this->createMock(AsyncService::class),
        );
        $orderManager->saveOrder(
            $customer->getId(),
            $executor->getId(),
            $description,
            $status,
            $price
        );
        $this->assertNotNull($orderRepository->findOneBy([
            'customer' => $userRepository->findOneBy(['login' => $customerLogin]),
            'executor' => $userRepository->findOneBy(['login' => $executorLogin]),
            'description' => $description,
            'status' => $status,
            'price' => $price,
        ]));
    }

    /**
     * @param string $customerId
     * @param string $executorId
     * @param string $description
     * @param int $status
     * @param float $price
     * @return void
     * @throws EntityNotFoundException
     * @throws \JsonException
     * @dataProvider orderFailSaveDataProvider
     */
    public function testFailSaveOrder(int $customerId, int $executorId, string $description, int $status, float $price): void
    {
        $orderManager = new OrderManager(
            $this->getDoctrineManager(),
            $this->createMock(TagAwareCacheInterface::class),
            $this->createMock(AsyncService::class),
        );
        $this->expectException(EntityNotFoundException::class);
        $orderManager->saveOrder(
            $customerId,
            $executorId,
            $description,
            $status,
            $price
        );
    }

    /**
     * @return void
     * @dataProvider orderSuccessUpdateDataProvider
     */
    public function testSuccessUpdateOrder(string $customerLogin, string $executorLogin, string $description, int $status, float $price): void
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrineManager()->getRepository(User::class);
        $customer = $userRepository->findOneBy(['login' => $customerLogin]);
        $executor = $userRepository->findOneBy(['login' => $executorLogin]);
        $orderRepository = $this->getDoctrineManager()->getRepository(Order::class);
        /** @var Order $order */
        $order = $orderRepository->findAll()[0];
        $orderManager = new OrderManager(
            $this->getDoctrineManager(),
            $this->createMock(TagAwareCacheInterface::class),
            $this->createMock(AsyncService::class),
        );
        $orderManager->updateOrder(
            $order->getId(), $customer->getId(), $executor->getId(), $description, $status, $price
        );
        $this->assertNotNull($orderRepository->findOneBy([
            'customer' => $userRepository->findOneBy(['login' => $customerLogin]),
            'executor' => $userRepository->findOneBy(['login' => $executorLogin]),
            'description' => $description,
            'status' => $status,
            'price' => $price,
        ]));
    }

    /**
     * @return void
     * @dataProvider orderFailUpdateDataProvider
     */
    public function testFailUpdateOrder(int $customerId, int $executorId, string $description, int $status, float $price): void
    {
        $orderRepository = $this->getDoctrineManager()->getRepository(Order::class);
        /** @var Order $order */
        $order = $orderRepository->findAll()[0];
        $orderManager = new OrderManager(
            $this->getDoctrineManager(),
            $this->createMock(TagAwareCacheInterface::class),
            $this->createMock(AsyncService::class),
        );
        $this->expectException(EntityNotFoundException::class);
        $orderManager->updateOrder(
            $order->getId(), $customerId, $executorId, $description, $status, $price
        );
    }

    /**
     * @param string $customerLogin
     * @param string $executorLogin
     * @param string $description
     * @param int $status
     * @param float $price
     * @return void
     * @dataProvider orderSuccessDeleteDataProvider
     */
    public function testSuccessDeleteOrder(string $customerLogin, string $executorLogin): void
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrineManager()->getRepository(User::class);
        $customer = $userRepository->findOneBy(['login' => $customerLogin]);
        $executor = $userRepository->findOneBy(['login' => $executorLogin]);
        $orderRepository = $this->getDoctrineManager()->getRepository(Order::class);
        /** @var Order $order */
        $order = $orderRepository->findOneBy([
            'customer' => $customer->getId(),
            'executor' => $executor->getId(),
        ]);
        $orderManager = new OrderManager(
            $this->getDoctrineManager(),
            $this->createMock(TagAwareCacheInterface::class),
            $this->createMock(AsyncService::class),
        );
        $orderManager->deleteOrder(
            $order
        );
        $this->assertNull($orderRepository->findOneBy([
            'customer' => $customer->getId(),
            'executor' => $executor->getId(),
        ]));
    }
}
