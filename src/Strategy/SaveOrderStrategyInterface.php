<?php
declare(strict_types=1);

namespace App\Strategy;

use App\Entity\Order;

interface SaveOrderStrategyInterface
{
    public function save(
        int $customerId,
        int $executorId,
        string $description,
        int $status,
        float $price
    ) : void;
}