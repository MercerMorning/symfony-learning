<?php

namespace App\Consumer\AddOrder;

use App\Consumer\AddOrder\Input\Message;
use App\Entity\User;
use App\Manager\OrderManager;
use App\Service\SubscriptionService;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class Consumer implements ConsumerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly OrderManager $orderManager
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
            $userRepository = $this->entityManager->getRepository(User::class);
            $customer = $userRepository->find($message->getCustomerId());
            $executor = $userRepository->find($message->getExecutorId());
            if (!($customer instanceof User)) {
                return $this->reject(sprintf('User ID %s was not found', $message->getCustomerId()));
            }
            if (!($executor instanceof User)) {
                return $this->reject(sprintf('User ID %s was not found', $message->getExecutorId()));
            }
            $this->orderManager->saveOrder(
                $customer,
                $executor,
                $message->getDescription(),
                $message->getStatus(),
                $message->getPrice()
            );
        } catch (Throwable $e) {
            return $this->reject($e->getMessage());
        } finally {
            $this->entityManager->clear();
            $this->entityManager->getConnection()->close();
        }

        return self::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }
}