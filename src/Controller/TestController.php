<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\UserProperty;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/test', name: 'app_test')]
    public function index(): JsonResponse
    {
//        $user = new User();
//        $user->setLogin('test_ex');
//        $this->entityManager->persist($user);
//        $this->entityManager->flush();

//        $repository = $this->entityManager->getRepository(User::class);
//        $user = $repository->find(1);
//        $userProperty = new UserProperty();
//        $userProperty->setName('name');
//        $userProperty->setValue('Tommy');
//        $userProperty->setUser($user);
//        $this->entityManager->persist($userProperty);
//        $this->entityManager->flush();

//        $repository = $this->entityManager->getRepository(User::class);
//        $user = $repository->find(1);

//        $repository = $this->entityManager->getRepository(User::class);
//        $customer = $repository->find(1);
//        $executor = $repository->find(2);
//        $order = new Order();
//        $order->setCustomer($customer);
//        $order->setExecutor($executor);
//        $order->setPrice(200);
//        $order->setDescription('test_desc');
//        $order->setStatus(1);
//        $this->entityManager->persist($order);
//        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(Order::class);
        $order = $repository->find(1);
        dd($order->toArray());

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }
}
