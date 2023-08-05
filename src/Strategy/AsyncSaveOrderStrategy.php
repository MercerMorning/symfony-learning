<?php
declare(strict_types=1);

namespace App\Strategy;

use App\DTO\SaveOrderDTO;
use App\Exception\CouldNotPublishQueueMessage;
use App\Service\AsyncService;

class AsyncSaveOrderStrategy implements SaveOrderStrategyInterface
{
    private AsyncService $asyncService;

    /**
     * @param AsyncService $asyncService
     */
    public function __construct(AsyncService $asyncService)
    {
        $this->asyncService = $asyncService;
    }

    public function save(int $customerId, int $executorId, string $description, int $status, float $price): void
    {
        $message = (new SaveOrderDTO($customerId, $executorId, $description, $status, $price))->toAMQPMessage();
        $result = $this->asyncService->publishToExchange(AsyncService::ADD_ORDER, $message);
        if ($result === false) {
            throw new CouldNotPublishQueueMessage('Message with order could not send');
        }
    }
}