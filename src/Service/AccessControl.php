<?php


namespace App\Service;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class AccessControl
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    public function isAbleToEditEntry(?object $entry): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($entry->getOwnedBy() !== $this->security->getUser()) {
            throw new AccessDeniedException('Недостаточно прав для редактирования записи');
        } else {
            return true;
        }
    }

    public function isAbleToEditUser(?object $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($user != $this->security->getUser()) {
            throw new AccessDeniedException('Недостаточно прав для редактирования записи');
        } else {
            return true;
        }
    }
}
