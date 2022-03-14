<?php

namespace App\Repository;

use App\Entity\Outing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Outing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outing[]    findAll()
 * @method Outing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Outing $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Outing $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    /**
     * @return Outing[] Returns an array of Outing objects
     */

    public function findByFilterOuting($criteria, $user)
    {

        $qb = $this->createQueryBuilder('o');

        if ($criteria->getCampus()) {
            $qb->andWhere('o.campus = :campus')
                ->setParameter('campus', $criteria->getCampus());
        }
        if ($criteria->getOutingNameLike()) {
            $qb->andWhere('o.name LIKE :searchBar')
                ->setParameter('searchBar', '%' . $criteria->getOutingNameLike() . '%');
        }
        if ($criteria->getStartingDate()) {
            $qb->andWhere('o.dateTimeStartOuting >= :startingDate')
                ->setParameter('startingDate', $criteria->getStartingDate());
        }
        if ($criteria->getEndingDate()) {
            $qb->andWhere('o.dateTimeStartOuting <= :endingDate')
                ->setParameter('endingDate', $criteria->getEndingDate());
        }
        if ($criteria->getIsOrganizer()) {
            $qb->andWhere('o.organizer = :user')
                ->setParameter('user', $user);
        }
        if ($criteria->getIsRegister()) {
            $qb->andWhere(':user MEMBER OF o.participants')
                ->setParameter('user', $user);
        }
        if ($criteria->getIsNotRegister()) {
            $qb->andWhere(':user NOT MEMBER OF o.participants')
                ->setParameter('user', $user);
        }
        if ($criteria->getPastOutings()) {
            $qb->andWhere('o.dateTimeStartOuting < :date')
                ->setParameter('date', new \DateTime("now"));
        }
        return $qb->getQuery()->getResult();

    }




    // /**
    //  * @return Outing[] Returns an array of Outing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Outing
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
