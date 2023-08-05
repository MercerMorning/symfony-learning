<?php

namespace App\Controller\Api\v1;

use App\DTO\SaveOrderDTO;
use App\Factory\SaveOrderStrategyFactory;
use App\Manager\OrderManager;
use App\Service\AsyncService;
use App\Strategy\SaveOrderStrategyInterface;
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
    private OrderManager $orderManager;
    private AuthorizationCheckerInterface $authorizationChecker;
    private TokenStorageInterface $tokenStorage;
    private SaveOrderStrategyFactory $saveOrderStrategyFactory;

    public function __construct(
        OrderManager                  $orderManager,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface         $tokenStorage,
        SaveOrderStrategyFactory $saveOrderStrategyFactory
    )
    {
        $this->orderManager = $orderManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->saveOrderStrategyFactory = $saveOrderStrategyFactory;
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
        $saveOrderStrategy = $this->saveOrderStrategyFactory->get($async === "0" ? 'sync' : 'async');
        $saveOrderStrategy->save(
            $customerId,
            $executorId,
            $description,
            $status,
            $price
        );
        return new JsonResponse(['success' => true], Response::HTTP_OK);
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
