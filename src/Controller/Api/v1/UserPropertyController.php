<?php

namespace App\Controller\Api\v1;

use App\Entity\User;
use App\Entity\UserProperty;
use App\Manager\UserPropertyManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'api/v1/user_property')]
class UserPropertyController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    public function __construct(private readonly UserPropertyManager $userPropertyManager)
    {
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveUserPropertyAction(Request $request): Response
    {
        $userId = $request->request->get('userId');
        $name = $request->request->get('name');
        $value = $request->request->get('value');
        $userId = $this->userPropertyManager->saveUserProperty($userId, $name, $value);
        [$data, $code] = $userId === null ?
            [['success' => false], Response::HTTP_BAD_REQUEST] :
            [['success' => true, 'userPropertyId' => $userId], Response::HTTP_OK];

        return new JsonResponse($data, $code);
    }

    #[Route(path: '', methods: ['GET'])]
    public function getUserPropertiesAction(Request $request): Response
    {
        $perPage = $request->request->get('perPage');
        $page = $request->request->get('page');
        $users = $this->userPropertyManager->getUserProperties($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse(['userProperties' => array_map(static fn(UserProperty $user) => $user->toArray(), $users)], $code);
    }

    #[Route(path: '/{user_property_id}', requirements: ['user_property_id' => '\d+'], methods: ['DELETE'])]
    #[Entity('user_property', expr: 'repository.find(user_property_id)')]
    public function deleteUserPropertyAction(UserProperty $userProperty): Response
    {
        $result = $this->userPropertyManager->deleteUserProperty($userProperty);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '', methods: ['PATCH'])]
    public function updateUserPropertyAction(Request $request): Response
    {
        $userPropertyId = $request->query->get('userPropertyId');
        $name = $request->query->get('name');
        $value = $request->query->get('value');
        $result = $this->userPropertyManager->updateUserProperty($userPropertyId, $name, $value);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
