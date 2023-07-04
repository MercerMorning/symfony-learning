<?php

namespace App\Controller;

use App\DTO\ManageUserPropertyDTO;
use App\Entity\UserProperty;
use App\Form\Type\UserPropertyType;
use App\Manager\UserPropertyManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\UserManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: 'user_property')]
class UserPropertyController extends AbstractController
{
    public function __construct(
        private readonly UserPropertyManager      $userPropertyManager,
        private readonly FormFactoryInterface     $formFactory,
    ) {
    }

    #[Route(path: '/create-user_property', name: 'create__user_property', methods: ['GET', 'POST'])]
    #[Route(path: '/update-user_property/{id}', name: 'update__user_property', methods: ['GET', 'POST'])]
    public function manageUserPropertyAction(Request $request, string $_route, ?int $id = null): Response
    {
        if ($id !== null) {
            $userProperty = $this->userPropertyManager->getUserPropertyById($id);
            $dto = ManageUserPropertyDTO::fromEntity($userProperty);
        }
        $form = $this->formFactory->create(
            UserPropertyType::class,
                $dto ?? null,
            ['isNew' => $_route === 'create__user_property']
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ManageUserPropertyDTO $userDto */
            $userPropertyDto = $form->getData();;
            $this->userPropertyManager->saveUserPropertyFromDTO($userProperty ?? new UserProperty(), $userPropertyDto);
        }

        return $this->renderForm('manageUserProperty.html.twig', [
            'form' => $form,
            'isNew' => $_route === 'create__user_property',
            'user' => $dto ?? null,
        ]);
    }
}