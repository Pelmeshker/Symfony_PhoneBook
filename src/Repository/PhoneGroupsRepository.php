<?php

namespace App\Repository;

use App\Entity\PhoneGroups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhoneGroups>
 *
 * @method PhoneGroups|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneGroups|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneGroups[]    findAll()
 * @method PhoneGroups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneGroupsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhoneGroups::class);
    }

    public function add(PhoneGroups $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PhoneGroups $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findGroupsByUser($user): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.owned_by = :u')
            ->setParameter('u', $user)
            ->getQuery()
            ->getResult();
    }

    public function findDefaultGroupByUser($user): PhoneGroups
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.isDefault = true and g.owned_by = :u')
            ->setParameters(['u' => $user])
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return PhoneGroups[] Returns an array of PhoneGroups objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PhoneGroups
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
