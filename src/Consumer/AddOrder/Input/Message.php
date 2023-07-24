<?php
declare(strict_types=1);

namespace App\Consumer\AddOrder\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    #[Assert\Type('numeric')]
    private int $customerId;

    #[Assert\Type('numeric')]
    private int $executorId;

    #[Assert\Type('string')]
    private string $description;

    #[Assert\Type('numeric')]
    private int $status;

    #[Assert\Type('numeric')]
    private float $price;

    public static function createFromQueue(string $messageBody): self
    {
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->customerId = $message['customerId'];
        $result->executorId = $message['executorId'];
        $result->description = $message['description'];
        $result->status = $message['status'];
        $result->price = $message['price'];
        return $result;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @return int
     */
    public function getExecutorId(): int
    {
        return $this->executorId;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}