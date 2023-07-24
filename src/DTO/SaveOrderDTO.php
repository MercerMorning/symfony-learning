<?php
declare(strict_types=1);

namespace App\DTO;

use JsonException;

class SaveOrderDTO
{
    private array $payload;

    public function __construct(
        int $customerId,
        int $executorId,
        string $description,
        int $status,
        float $price,
    )
    {
        $this->payload = [
            'customerId' => $customerId,
            'executorId' => $executorId,
            'description' => $description,
            'status' => $status,
            'price' => $price,
        ];
    }

    /**
     * @throws JsonException
     */
    public function toAMQPMessage(): string
    {
        return json_encode($this->payload, JSON_THROW_ON_ERROR);
    }
}