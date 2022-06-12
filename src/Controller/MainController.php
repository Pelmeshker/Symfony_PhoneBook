<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\PhoneEntry;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route("/")]
    public function showMain(ManagerRegistry $doctrine): Response
    {
        $entries = $doctrine->getRepository(PhoneEntry::class)->findAll();
        return $this->render('Index.html.twig', ['entries' => $entries]
        );
    }

    #[Route('/addEntry', name: 'add_entry')]
    public function addEntry(): Response
    {
        return $this->render('add_entry.twig');
    }
}
