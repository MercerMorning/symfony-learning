<?php

namespace App\Controller\Api\v1;

use App\DTO\SaveOrderDTO;
use App\Manager\OrderManager;
use App\Service\AsyncService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route(path: 'api/v1/order')]
class OrderController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    private OrderManager $orderManager;
    private AuthorizationCheckerInterface $authorizationChecker;
    private AsyncService $asyncService;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        OrderManager                  $orderManager,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface         $tokenStorage,
        AsyncService $asyncService
    )
    {
        $this->orderManager = $orderManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->asyncService = $asyncService;
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveOrderAction(Request $request): Response
    {
        $async = $request->request->get('async');
        $customerId = $this->tokenStorage->getToken()->getUser()->getUserIdentifier();
        $executorId = $request->request->get('executorId');
        $description = $request->request->get('description');
        $status = $request->request->get('status');
        $price = $request->request->get('price');
        $orderId = $this->orderManager->saveOrder(
            $customerId,
            $executorId,
            $description,
            $status,
            $price
        );
        if ($async === 0) {
            $orderId = $this->orderManager->saveOrder(
                $customerId,
                $executorId,
                $description,
                $status,
                $price
            );
            [$data, $code] = $orderId === null ?
                [['success' => false], Response::HTTP_BAD_REQUEST] :
                [['success' => true, 'orderId' => $orderId], Response::HTTP_OK];
        } else {
            $message = (new SaveOrderDTO($customerId, $executorId, $description, $status, $price))->toAMQPMessage();
            $result = $this->asyncService->publishToExchange(AsyncService::ADD_ORDER, $message);
            [$data, $code] = $result ?
                [['success' => false], Response::HTTP_BAD_REQUEST] :
                [['success' => true, 'orderId' => $orderId], Response::HTTP_ACCEPTED];
        }
        return new JsonResponse($data, $code);
    }

    #[Route(path: '', methods: ['PATCH'])]
    public function updateOrderAction(Request $request): Response
    {
        $orderId = $request->query->get('orderId');
        if (!$this->authorizationChecker->isGranted('update_entity', $this->orderManager->getOrderById($orderId))) {
            return new JsonResponse('Access denied', Response::HTTP_FORBIDDEN);
        }
        $customerId = $request->query->get('customerId');
        $executorId = $request->query->get('executorId');
        $description = $request->query->get('description');
        $status = $request->query->get('status');
        $price = $request->query->get('price');
        $result = $this->orderManager->updateOrder(
            $orderId,
            $customerId,
            $executorId,
            $description,
            $status,
            $price
        );

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
