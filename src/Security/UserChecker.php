<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof \App\Entity\User) {
            return;
        }
        if (!$user->getIsActive()) {
            throw new CustomUserMessageAccountStatusException('Votre compte est suspendu.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Pas d'action post-authentification
    }
}
