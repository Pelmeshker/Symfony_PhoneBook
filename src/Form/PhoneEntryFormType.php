<?php

namespace App\Form;

use App\Entity\PhoneEntry;
use App\Entity\PhoneGroups;
use App\Entity\User;
use App\Repository\PhoneGroupsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class PhoneEntryFormType extends AbstractType
{

    private $groupRepository;
    private $doctrine;
    private $security;

    public function __construct(PhoneGroupsRepository $groupRepository, ManagerRegistry $doctrine, Security $security)
    {
        $this->groupRepository = $groupRepository;
        $this->doctrine = $doctrine;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->doctrine->getRepository(User::class)->find($this->security->getUser());

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $choices = $this->groupRepository->findAll();
        } else {
            $choices = $this->groupRepository->findGroupsByUser($user);
        }
        $builder
            ->add('name')
            ->add('number')
            ->add('description')
            ->add('entryGroups', EntityType::class, [
                'class' => PhoneGroups::class,
                'choices' => $choices,
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('priority');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PhoneEntry::class,
        ]);
    }
}
