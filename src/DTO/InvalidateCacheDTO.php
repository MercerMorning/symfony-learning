<?php
declare(strict_types=1);

namespace App\DTO;

use JsonException;

class InvalidateCacheDTO
{
    private array $payload;

    public function __construct(
        string $tag,
    )
    {
        $this->payload = [
            'tag' => $tag
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