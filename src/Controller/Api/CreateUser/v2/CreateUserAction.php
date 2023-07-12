<?php

namespace App\Controller\Api\CreateUser\v2;

use App\DTO\ManageUserDTO;
use App\Entity\User;
use App\Manager\UserManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class CreateUserAction extends AbstractFOSRestController
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @throws JsonException
     */
    #[Rest\Post(path: '/api/v2/user')]
    #[RequestParam(name: 'login')]
    #[RequestParam(name: 'password')]
    #[RequestParam(name: 'roles', default: '{}')]
    public function __invoke(
        string $login,
        string $password,
        string $roles,
    ): Response
    {
        $userDTO = new ManageUserDTO(...[
            'login' => $login,
            'password' => $password,
            'roles' => json_decode($roles, true, 512, JSON_THROW_ON_ERROR),
        ]);

        $userId = $this->userManager->saveUserFromDTO(new User(), $userDTO);
        [$data, $code] = ($userId === null) ? [['success' => false], 400] : [['id' => $userId], 200];

        return $this->handleView($this->view($data, $code));
    }
}