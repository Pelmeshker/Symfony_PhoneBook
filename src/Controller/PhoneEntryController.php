<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PhoneEntryFormType;
use App\Form\RegistrationFormType;
use App\Entity\PhoneEntry;
use App\Repository\PhoneEntryRepository;
use App\Security\UserChecker;
use App\Service\AccessControl;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class PhoneEntryController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/addEntry', name: 'add_entry')]
    public function addEntry(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entry = new PhoneEntry();
        $entry->setOwnedBy($this->getUser());

        $form = $this->createForm(PhoneEntryFormType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entry);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_main_showmain');
        }

        return $this->render('add_entry.html.twig', [
            'entryForm' => $form->createView(),
        ]);
    }

    #[Route('/editEntry/{id}', name: 'edit_entry')]
    public function editEntry(Request $request, EntityManagerInterface $entityManager, int $id,
                              ManagerRegistry $doctrine, AccessControl $checker): Response
    {
        $entry = $doctrine->getRepository(PhoneEntry::class)->find($id);
        if ($checker->isAbleToEditEntry($entry)) {

            $form = $this->createForm(PhoneEntryFormType::class, $entry);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($entry);
                $entityManager->flush();
                // do anything else you need here, like send an email
                return $this->redirectToRoute('app_main_showmain');
            }
            return $this->render('add_entry.html.twig', [
                'entryForm' => $form->createView(),
            ]);
        }
    }

    #[
        Route('/deleteEntry/{id}', name: 'delete_entry')]
    public function deleteEntry(Request $request, EntityManagerInterface $entityManager, int $id,
                                ManagerRegistry $doctrine, AccessControl $checker): Response
    {

        $entry = $doctrine->getRepository(PhoneEntry::class)->find($id);
        if ($checker->isAbleToEditEntry($entry)) {
            $entityManager->remove($entry);
            $entityManager->flush();
            return $this->redirectToRoute('app_main_showmain');
        }
    }

    #[Route('/saveEntry', name: 'save_entry')]
    public function saveEntry(PhoneEntry $phoneEntry): Response
    {
        $EntryRepo = new PhoneEntryRepository();
        $EntryRepo->add($phoneEntry);
        return $this->render('Index.html.twig');
    }

}
