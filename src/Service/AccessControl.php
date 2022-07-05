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

        //довольно убогий способ проверки принадлежности пользователя к редактированию контанта, но работает
        //при добавлении функционала передачи контактов другим пользователям нужно его переписать
        $group = $entry->getEntryGroups();
        $certainGroup = null;
        foreach ($group as $firstGroup) {
            $certainGroup = $firstGroup;
        }

        if ($certainGroup->getOwnedBy() !== $this->security->getUser()) {
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

    public function isAbleToEditGroup(?object $group): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if ($group->getOwnedBy() !== $this->security->getUser()) {
            throw new AccessDeniedException('Недостаточно прав для редактирования записи');
        } else {
            return true;
        }
    }

    public function isAbleToDeleteGroup(?object $group): bool
    {
        if ($group->isIsDefault()) {
            throw new AccessDeniedException('Это дефолтная группа, ее нельзя удалять');
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if ($group->getOwnedBy() !== $this->security->getUser()) {
            throw new AccessDeniedException('Недостаточно прав для редактирования записи');
        } else {
            return true;
        }
    }
}
