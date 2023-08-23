<?php

namespace App\Manager;

use App\DTO\CreateOrderDTO;
use App\Entity\Order;
use App\Entity\User;
use App\ExceptionHandler\ExceptionHandlerInterface;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\AsyncService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class OrderManager
{
    private EntityManagerInterface $entityManager;
    private TagAwareCacheInterface $cache;
    private AsyncService $asyncService;

    private const CACHE_TAG = 'orders';

    public function __construct(
        EntityManagerInterface    $entityManager,
        TagAwareCacheInterface    $cache,
        AsyncService              $asyncService,
    )
    {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
        $this->asyncService = $asyncService;
    }

    /**
     * @throws \JsonException
     * @throws EntityNotFoundException
     */
    public function saveOrder(
        int    $customerId,
        int    $executorId,
        string $description,
        int    $status,
        float  $price
    ): ?int
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $customer = $userRepository->find($customerId);
        $executor = $userRepository->find($executorId);
        if ($customer === null || $executor === null) {
            throw new EntityNotFoundException();
        }
        $order = new Order();
        $order->setCustomer($customer);
        $order->setExecutor($executor);
        $order->setDescription($description);
        $order->setStatus($status);
        $order->setPrice($price);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        $message = new CreateOrderDTO($order->getId());
        $this->asyncService->publishToExchange(AsyncService::CREATE_ORDER, $message->toAMQPMessage());
        return $order->getId();
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateOrder(
        int    $orderId,
        int    $customerId,
        int    $executorId,
        string $description,
        int    $status,
        float  $price
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
        if ($customer === null || $executor === null) {
            throw new EntityNotFoundException();
        }
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

        return $this->cache->get(
            "orders_{$page}_{$perPage}",
            function (ItemInterface $item) use ($orderRepository, $page, $perPage) {
                $orders = $orderRepository->getOrders($page, $perPage);
                $ordersSerialized = array_map(static fn(Order $order) => $order->toArray(), $orders);
                $item->set($ordersSerialized);
                $item->tag(self::CACHE_TAG);
                return $ordersSerialized;
            }
        );
    }

    public function getOrderById(int $id): ?Order
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->entityManager->getRepository(Order::class);

        return $orderRepository->find($id);
    }
}