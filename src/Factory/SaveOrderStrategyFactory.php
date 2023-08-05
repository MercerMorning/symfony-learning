<?php
declare(strict_types=1);

namespace App\Factory;

use App\Strategy\SaveOrderStrategyInterface;
use RuntimeException;

class SaveOrderStrategyFactory
{
    private array $strategies = [];

    public function registerStrategy(string $type, SaveOrderStrategyInterface $saveOrderStrategy)
    {
        $this->strategies[$type] = $saveOrderStrategy;
    }

    public function get(string $type): SaveOrderStrategyInterface
    {
        if (!isset($this->strategies[$type])) {
            throw new RuntimeException('Strategy type: ' . $type . ' does not exist');
        }
        return $this->strategies[$type];
    }
}