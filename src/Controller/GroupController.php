<?php

namespace App\Controller;

use App\Entity\PhoneGroups;
use App\Form\GroupFormType;
use App\Service\AccessControl;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class GroupController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route("/groups", name: 'groups')]
    public function showAdmin(ManagerRegistry $doctrine): Response
    {
        if ($this->security->isGranted('ROLE_USER')) {
            $groups = $doctrine->getRepository(PhoneGroups::class)->findBy(['owned_by' => $this->getUser()], ['priority' => 'DESC']);
            return $this->render('groups.html.twig', ['groups' => $groups]);
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $groups = $doctrine->getRepository(PhoneGroups::class)->findBy([], ['priority' => 'DESC']);
            return $this->render('groups.html.twig', ['groups' => $groups]);
        }
    }

    #[Route('/addGroup', name: 'add_group')]
    public function addGroup(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new PhoneGroups();
        $group->setOwnedBy($this->getUser());
        $group->setIsDefault(false);

        $form = $this->createForm(GroupFormType::class, $group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($group);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('groups');
        }

        return $this->render('add_group.html.twig', [
            'groupForm' => $form->createView(),
        ]);
    }

    #[Route('/editGroup/{id}', name: 'edit_group')]
    public function editGroup(Request $request, EntityManagerInterface $entityManager, int $id,
                              ManagerRegistry $doctrine, AccessControl $checker): Response
    {
        $group = $doctrine->getRepository(PhoneGroups::class)->find($id);
        if ($checker->isAbleToEditGroup($group)) {

            $form = $this->createForm(GroupFormType::class, $group);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($group);
                $entityManager->flush();
                // do anything else you need here, like send an email
                return $this->redirectToRoute('app_main_showmain');
            }
            return $this->render('add_group.html.twig', [
                'groupForm' => $form->createView(),
            ]);
        }
    }

    #[Route('/deleteGroup/{id}', name: 'delete_group')]
    public function deleteGroup(Request $request, EntityManagerInterface $entityManager, int $id,
                                ManagerRegistry $doctrine, AccessControl $checker): Response
    {
        $group = $doctrine->getRepository(PhoneGroups::class)->find($id);
        if ($checker->isAbleToDeleteGroup($group)) {
            $entityManager->remove($group);
            $entityManager->flush();
            return $this->redirectToRoute('groups');
        }
    }
}
