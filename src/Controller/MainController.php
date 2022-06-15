<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\PhoneEntry;
use App\Entity\User;
use App\Form\PhoneEntryFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route("/")]
    public function showMain(ManagerRegistry $doctrine): Response
    {

        if ($this->security->isGranted('ROLE_USER')) {
            $entries = $doctrine->getRepository(PhoneEntry::class)->findBy(
                ['owned_by' => $this->getUser()]
            );
            return $this->render('Index.html.twig', ['entries' => $entries]);
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $entries = $doctrine->getRepository(PhoneEntry::class)->findAll();
            return $this->render('Index.html.twig', ['entries' => $entries]);
        }
    }

    #[Route("/admin", name: 'admin')]
    public function showAdmin(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();
        return $this->render('admin.html.twig', ['users' => $users]);
    }

    #[Route('/editUser/{id}', name: 'edit_user')]
    public function editUser(Request $request, EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine): Response
    {

        $user = $doctrine->getRepository(User::class)->find($id);

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('admin');
        }

        return $this->render('edit_user.html.twig', [
            'userForm' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/changeUserRole/{id}', name: 'change_user_role')]
    public function changeUserRole(EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        if ($user->getRoles() == ["ROLE_USER"]) {
            $user->setRoles(["ROLE_ADMIN"]);
        }
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
            $user->setRoles(["ROLE_USER"]);
        } else {
                //Если внезапно роль окажется другой, то ничего не произойдет
        }
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin');
    }

}
