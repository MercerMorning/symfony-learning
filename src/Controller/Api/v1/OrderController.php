<?php

namespace App\Controller\Api\v1;

use App\Entity\Order;
use App\Entity\UserProperty;
use App\Manager\OrderManager;
use App\Manager\UserPropertyManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'api/v1/order')]
class OrderController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    public function __construct(private readonly OrderManager $orderManager)
    {
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveOrderAction(Request $request): Response
    {
        $customerId = $request->request->get('customerId');
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
        [$data, $code] = $orderId === null ?
            [['success' => false], Response::HTTP_BAD_REQUEST] :
            [['success' => true, 'orderId' => $orderId], Response::HTTP_OK];

        return new JsonResponse($data, $code);
    }

    #[Route(path: '', methods: ['GET'])]
    public function getUserPropertiesAction(Request $request): Response
    {
        $perPage = $request->request->get('perPage');
        $page = $request->request->get('page');
        $users = $this->orderManager->getOrders($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse(['orders' => array_map(static fn(Order $user) => $user->toArray(), $users)], $code);
    }

    #[Route(path: '/{order_id}', requirements: ['order_id' => '\d+'], methods: ['DELETE'])]
    #[Entity('order', expr: 'repository.find(order_id)')]
    public function deleteUserPropertyAction(Order $order): Response
    {
        $result = $this->orderManager->deleteUserProperty($order);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '', methods: ['PATCH'])]
    public function updateUserPropertyAction(Request $request): Response
    {
        $orderId = $request->query->get('orderId');
        $customerId = $request->request->get('customerId');
        $executorId = $request->request->get('executorId');
        $description = $request->request->get('description');
        $status = $request->request->get('status');
        $price = $request->request->get('price');
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
