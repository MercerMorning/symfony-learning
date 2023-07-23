<?php

namespace App\Security\Voter;

use App\Entity\HasOwnerInterface;
use App\Entity\Order;
use App\Manager\UserPropertyManager;
use App\Security\AuthUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanInteractToHasOwnerEntityVoter extends Voter
{
    const ACTIONS = [
        'update_entity',
        'delete_entity'
    ];


    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, self::ACTIONS) && $subject instanceof HasOwnerInterface;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof AuthUser) {
            return false;
        }

        /**
         * @var $subject Order
         */
        return $subject->getCustomer()->getId() === $user->getId();
    }
}