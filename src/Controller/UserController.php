<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordFormType;
use App\Form\EditSelfUserFormType;
use App\Service\AccessControl;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/editUserPassword/{id}', name: 'edit_user_password')]
    public function register(Request $request, ManagerRegistry $doctrine, int $id,
                             UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, AccessControl $checker): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        if ($checker->isAbleToEditUser($user)) {
            $form = $this->createForm(EditPasswordFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                // do anything else you need here, like send an email

                return $this->redirectToRoute('app_main_showmain');
            }
            return $this->render('edit_user_password.html.twig', [
                'changeUserPasswordForm' => $form->createView(),
            ]);
        }
    }

    #[Route('/editSelfUser/{id}', name: 'edit_selfuser')]
    public function editSelfUser(Request $request, EntityManagerInterface $entityManager, int $id,
                                 ManagerRegistry $doctrine, AccessControl $checker): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        if ($checker->isAbleToEditUser($user)) {
            $form = $this->createForm(EditSelfUserFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email

                return $this->redirectToRoute('app_main_showmain');
            }

            return $this->render('edit_self_user.html.twig', [
                'userForm' => $form->createView(),
                'user' => $user
            ]);
        }
    }

}
