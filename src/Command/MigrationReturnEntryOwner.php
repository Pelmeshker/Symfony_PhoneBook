<?php


namespace App\Command;


use App\Entity\PhoneEntry;
use App\Entity\PhoneGroups;
use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class MigrationReturnEntryOwner extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'app:return-owner';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entries = $this->entityManager->getRepository(PhoneEntry::class)->findAll();
        foreach ($entries as $entry) {
            $entryGroups = $entry->getEntryGroups();
            foreach ($entryGroups as $eachGroup) {
                $copyEntry = new PhoneEntry();
                $copyEntry->setName($entry->getName());
                $copyEntry->setNumber($entry->getNumber());
                $copyEntry->setDescription($entry->getDescription());
                $copyEntry->setPriority($entry->getPriority());

                $user = $eachGroup->getOwnedBy();
                $copyEntry->setOwnedBy($user);
                $this->entityManager->persist($copyEntry);
                $this->entityManager->remove($entry);
                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }
}
