<?php

namespace App\Controller\Api\v1\Admin;

use App\DTO\ManageUserDTO;
use App\Entity\User;
use App\Manager\UserManager;
use App\Security\Voter\UserVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route(path: 'api/v1/admin/user')]
class UserController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveUserAction(Request $request): Response
    {
        $saveUserDTO = ManageUserDTO::fromRequest($request);
        $userId = $this->userManager->saveUserFromDTO(new User(), $saveUserDTO);
        [$data, $code] = $userId === null ?
            [['success' => false], Response::HTTP_BAD_REQUEST] :
            [['success' => true, 'userId' => $userId], Response::HTTP_OK];

        return new JsonResponse($data, $code);
    }

    #[Route(path: '', methods: ['GET'])]
    public function getUsersAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $users = $this->userManager->getUsers($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse(['users' => array_map(static fn(User $user) => $user->toArray(), $users)], $code);
    }

    #[Route(path: '/by-login/{user_login}', methods: ['GET'], priority: 2)]
    #[ParamConverter('user', options: ['mapping' => ['user_login' => 'login']])]
    public function getUserByLoginAction(User $user): Response
    {
        return new JsonResponse(['user' => $user->toArray()], Response::HTTP_OK);
    }

    #[Route(path: '/{user_id}', requirements: ['user_id' => '\d+'], methods: ['DELETE'])]
    #[Entity('user', expr: 'repository.find(user_id)')]
    public function deleteUserAction(User $user): Response
    {
        $result = $this->userManager->deleteUser($user);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '', methods: ['PATCH'])]
    public function updateUserAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $login = $request->query->get('login');
        $result = $this->userManager->updateUser($userId, $login);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}