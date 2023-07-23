<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveOrder(
        int $customerId,
        int $executorId,
        string $description,
        int $status,
        float $price
    ): ?int
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $customer = $userRepository->find($customerId);
        $executor = $userRepository->find($executorId);
        $order = new Order();
        $order->setCustomer($customer);
        $order->setExecutor($executor);
        $order->setDescription($description);
        $order->setStatus($status);
        $order->setPrice($price);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        return $order->getId();
    }

    public function updateOrder(
        int $orderId,
        int $customerId,
        int $executorId,
        string $description,
        int $status,
        float $price
    ): bool
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->entityManager->getRepository(Order::class);
        /** @var Order $order */
        $order = $orderRepository->find($orderId);
        if ($order === null) {
            return false;
        }
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $customer = $userRepository->find($customerId);
        $executor = $userRepository->find($executorId);
        $order->setCustomer($customer);
        $order->setExecutor($executor);
        $order->setDescription($description);
        $order->setStatus($status);
        $order->setPrice($price);
        $this->entityManager->flush();

        return true;
    }

    public function deleteOrder(Order $order): bool
    {
        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return User[]
     */
    public function getOrders(int $page, int $perPage): array
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->entityManager->getRepository(Order::class);

        return $orderRepository->getOrders($page, $perPage);
    }

    public function getOrderById(int $id): ?Order
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->entityManager->getRepository(Order::class);

        return $orderRepository->find($id);
    }
}