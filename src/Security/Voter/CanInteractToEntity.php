<?php

namespace App\Security\Voter;

use App\Security\AuthUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanInteractToEntity extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof AuthUser) {
            return false;
        }
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}