<?php

namespace App\Consumer\CreateOrder;

use App\Consumer\CreateOrder\Input\Message;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Throwable;

class Consumer implements ConsumerInterface
{

    public function __construct(
        private readonly TagAwareCacheInterface $cache
    )
    {
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
           $this->cache->invalidateTags(['orders']);
        } catch (Throwable $e) {
            return $this->rejectAndRequeue($e->getMessage());
        }

        return self::MSG_ACK;
    }

    private function rejectAndRequeue(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT_REQUEUE;
    }
}