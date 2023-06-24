<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\UserProperty;
use App\Service\FormatService;
use App\Service\GreeterService;
use App\Service\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractController
{
    public function __construct(
        private readonly FormatService $formatService,
        private readonly MessageService $messageService,
    )
    {
    }

    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        $result = $this->formatService->format($this->messageService->printMessages('world'));

        return new Response("<html><body>$result</body></html>");
    }
}
