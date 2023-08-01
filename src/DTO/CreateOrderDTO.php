<?php
declare(strict_types=1);

namespace App\DTO;

use JsonException;

class CreateOrderDTO
{
    private array $payload;

    public function __construct(
        int $id,
    )
    {
        $this->payload = [
            'id' => $id
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