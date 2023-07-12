<?php

namespace App\Controller\Api\CreateUser\v3;

use App\Controller\Api\CreateUser\v3\Input\CreateUserDTO;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class CreateUserAction extends AbstractFOSRestController
{

    private CreateUserManager $saveUserManager;

    public function __construct(CreateUserManager $saveUserManager)
    {
        $this->saveUserManager = $saveUserManager;
    }

    #[Rest\Post(path: '/api/v3/user')]
    public function saveUserAction(#[MapRequestPayload] CreateUserDTO $request): Response
    {
        $user = $this->saveUserManager->saveUser($request);
        [$data, $code] = ($user->id === null) ? [['success' => false], 400] : [['user' => $user], 200];

        return $this->handleView($this->view($data, $code));
    }
}