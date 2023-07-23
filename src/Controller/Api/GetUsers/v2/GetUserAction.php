<?php

namespace App\Controller\Api\GetUsers\v2;

use App\Manager\UserManager;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetUserAction extends AbstractFOSRestController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    #[Rest\Get(path: '/api/v2/user.{format}')]
    public function __invoke(
        #[MapQueryParameter] ?int $perPage,
        #[MapQueryParameter] ?int $page,
        string $format): Response
    {
        $users = $this->userManager->getUsers($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $context = (new Context())->setGroups(['main-user-info', 'user-id-list']);

        return $this->handleView($this->view(['users' => $users], $code)->setContext($context)->setFormat($format));
    }
}