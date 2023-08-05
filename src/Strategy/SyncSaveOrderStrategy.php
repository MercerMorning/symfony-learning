<?php
declare(strict_types=1);

namespace App\Strategy;

use App\Exception\CouldNotCreateEntity;
use App\Manager\OrderManager;

class SyncSaveOrderStrategy implements SaveOrderStrategyInterface
{
    private OrderManager $orderManager;

    /**
     * @param OrderManager $orderManager
     */
    public function __construct(OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    public function save(
        int    $customerId,
        int    $executorId,
        string $description,
        int    $status,
        float  $price
    ): void
    {
        $orderId = $this->orderManager->saveOrder(
            $customerId,
            $executorId,
            $description,
            $status,
            $price
        );
        if ($orderId === null) {
            throw new CouldNotCreateEntity('Could not create order');
        }
    }
}