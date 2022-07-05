<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\PhoneEntry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            $entries = $doctrine->getRepository(PhoneEntry::class)->findEntriesByUser($this->getUser());
            return $this->render('Index.html.twig', ['entries' => $entries]);
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $entries = $doctrine->getRepository(PhoneEntry::class)->findAllEntries();
            return $this->render('Index.html.twig', ['entries' => $entries]);
        }
    }

}
