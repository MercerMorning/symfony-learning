<?php

namespace App\Security\Voter;

use App\Entity\UserProperty;
use App\Manager\UserPropertyManager;
use App\Security\AuthUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanInteractToUserPropertyVoter extends Voter
{
    const ACTIONS = [
        'update_entity',
        'delete_entity'
    ];

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, self::ACTIONS) && $subject instanceof UserProperty;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof AuthUser) {
            return false;
        }

        return $subject->getUser()->getId() === $user->getId();
    }
}