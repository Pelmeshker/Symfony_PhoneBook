<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\PhoneEntry;
use App\Entity\PhoneGroups;
use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[IsGranted("ROLE_ADMIN")]
class AdminController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
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
        } elseif ($user->getRoles() == ["ROLE_ADMIN"]) {
            $user->setRoles(["ROLE_USER"]);
        } else {
            //Если внезапно роль окажется другой, то ничего не произойдет
        }
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin');
    }

    #[Route('/deleteUser/{id}', name: 'delete_user')]
    public function deleteUser(EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        $entityManager->getConnection()->beginTransaction();
        try {
            $groups = $entityManager->getRepository(PhoneGroups::class)->findBy(['owned_by' => $user]);
            foreach ($groups as $group) {
                $entityManager->remove($group);
            }
            $entityManager->remove($user);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
            return $this->redirectToRoute('admin');
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
            throw $e;


            return $this->redirectToRoute('admin');
        }
    }
}
