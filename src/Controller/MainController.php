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
                ['owned_by' => $this->getUser()], ['priority' => 'DESC']
            );
            return $this->render('Index.html.twig', ['entries' => $entries]);
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $entries = $doctrine->getRepository(PhoneEntry::class)->findBy(
                [], ['priority' => 'DESC']
            );
            return $this->render('Index.html.twig', ['entries' => $entries]);
        }
    }

}
