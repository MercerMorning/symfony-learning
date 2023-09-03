<?php

namespace App\Controller\Api\v1;

use App\Entity\User;
use App\Entity\Skill;
use App\Manager\UserManager;
use App\Manager\UserPropertyManager;
use App\Security\Voter\CanInteractToUserPropertyVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route(path: 'api/v1/user_property')]
class UserPropertyController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    private UserPropertyManager $userPropertyManager;
    private AuthorizationCheckerInterface $authorizationChecker;
    private UserManager $userManager;

    public function __construct(
        UserPropertyManager $userPropertyManager,
        AuthorizationCheckerInterface $authorizationChecker,
        UserManager $userManager,
    )
    {
        $this->userPropertyManager = $userPropertyManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->userManager = $userManager;
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveUserPropertyAction(Request $request): Response
    {
        $userId = $this->tokenStorage->getToken()->getUser()->getUserIdentifier();
        $name = $request->request->get('name');
        $value = $request->request->get('value');
        $userId = $this->userPropertyManager->saveUserProperty($userId, $name, $value);
        [$data, $code] = $userId === null ?
            [['success' => false], Response::HTTP_BAD_REQUEST] :
            [['success' => true, 'userPropertyId' => $userId], Response::HTTP_OK];

        return new JsonResponse($data, $code);
    }

    #[Route(path: '/{user_property_id}', requirements: ['user_property_id' => '\d+'], methods: ['DELETE'])]
    #[Entity('userProperty', expr: 'repository.find(user_property_id)')]
    public function deleteUserPropertyAction(Skill $userProperty): Response
    {
        if (!$this->authorizationChecker->isGranted('delete_entity', $userProperty)) {
            return new JsonResponse('Access denied', Response::HTTP_FORBIDDEN);
        }
        $result = $this->userPropertyManager->deleteUserProperty($userProperty);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '', methods: ['PATCH'])]
    public function updateUserPropertyAction(Request $request): Response
    {
        $userPropertyId = $request->query->get('userPropertyId');
        if (!$this->authorizationChecker->isGranted(
            'update_entity',
            $this
                ->userPropertyManager
                ->getUserPropertyById($userPropertyId))
        ) {
            return new JsonResponse('Access denied', Response::HTTP_FORBIDDEN);
        }
        $name = $request->query->get('name');
        $value = $request->query->get('value');
        $result = $this->userPropertyManager->updateUserProperty($userPropertyId, $name, $value);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
