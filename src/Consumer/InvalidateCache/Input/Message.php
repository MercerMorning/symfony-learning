<?php
declare(strict_types=1);

namespace App\Consumer\InvalidateCache\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    #[Assert\Type('string')]
    private string $cacheTag;

    public static function createFromQueue(string $messageBody): self
    {
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->cacheTag = $message['tag'];
        return $result;
    }

    /**
     * @return string
     */
    public function getCacheTag(): string
    {
        return $this->cacheTag;
    }

}