<?php

namespace App\Security\Voter;

use App\Security\AuthUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanCreateEntity extends Voter
{
    const ACTION = 'create_entity';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::ACTION && (int)$subject == $subject;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof AuthUser) {
            return false;
        }
        return $user->getId() == $subject;
    }
}