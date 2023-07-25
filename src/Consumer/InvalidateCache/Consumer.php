<?php

namespace App\Consumer\InvalidateCache;

use App\Consumer\InvalidateCache\Input\Message;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Throwable;

class Consumer implements ConsumerInterface
{

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly TagAwareCacheInterface $cache
    )
    {
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = Message::createFromQueue($msg->getBody());
            $errors = $this->validator->validate($message);
            if ($errors->count() > 0) {
                return $this->reject((string)$errors);
            }
        } catch (JsonException $e) {
            return $this->reject($e->getMessage());
        }

        try {
           dump($this->cache->invalidateTags([$message->getCacheTag()]));
        } catch (Throwable $e) {
            return $this->reject($e->getMessage());
        }

        return self::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }
}