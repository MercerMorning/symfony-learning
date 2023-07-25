<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Exception\CouldNotCreateEntity;
use App\Exception\CouldNotPublishQueueMessage;
use App\Factory\JsonResponseFactory;
use Doctrine\ORM\EntityNotFoundException;
use InvalidArgumentException;
use JsonException;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ExceptionListener
{
    private $supportedExceptions = [
        CouldNotCreateEntity::class,
        CouldNotPublishQueueMessage::class
    ];
    private JsonResponseFactory $jsonResponseFactory;

    /**
     * @param JsonResponseFactory $jsonResponseFactory
     */
    public function __construct(JsonResponseFactory $jsonResponseFactory)
    {
        $this->jsonResponseFactory = $jsonResponseFactory;
    }

    public function addSupportedException(Throwable $throwable): void
    {
        $this->supportedExceptions[] = $throwable;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        foreach ($this->supportedExceptions as $supportedException) {
            if ($exception instanceof  $supportedException) {
                $event->setResponse(new JsonResponse(['success' => 'fail'], Response::HTTP_BAD_REQUEST));
            }
        }
    }
}