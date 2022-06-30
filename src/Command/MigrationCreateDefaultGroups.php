<?php


namespace App\Command;


use App\Entity\PhoneEntry;
use App\Entity\PhoneGroups;
use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class MigrationCreateDefaultGroups extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'app:create-groups';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $group = new PhoneGroups();
            $group->setName("DefaultUserGroup");
            $group->setIsDefault(true);
            $group->setDescription("Дефолтная группа пользователя " . $user->getUsername());
            $group->setOwnedBy($user);
            $group->setPriority(1);
            $this->entityManager->persist($group);
            $this->entityManager->flush();

        }

        $entries = $this->entityManager->getRepository(PhoneEntry::class)->findAll();
        foreach ($entries as $entry) {
            $groups = $this->entityManager->getRepository(PhoneGroups::class)->findBy(['owned_by' => $entry->getOwnedBy()]);
            foreach ($groups as $group) {
                $entry->addEntryGroup($group);
                $this->entityManager->persist($entry);
                $this->entityManager->flush();
            }
        }

        // вернуть это, если при выполнении команды не было проблем
        // (равноценно возвращению int(0))
        return Command::SUCCESS;

        // или вернуть это, если во время выполнения возникла ошибка
        // (равноценно возвращению int(1))
        // return Command::FAILURE;

        // или вернуть это, чтобы указать на неправильное использование команды, например, невалидные опции
        // или отсутствующие аргументы (равноценно возвращению int(2))
        // return Command::INVALID
    }
}
