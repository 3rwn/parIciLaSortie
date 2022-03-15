<?php

namespace App\Repository;

use App\Entity\Outing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use function Symfony\Component\String\s;

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
        $qb = $this->createQueryBuilder('o')
        ->innerJoin('o.state','s');

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
            $qb->andWhere('s.id = :state')
                ->setParameter('state', 5);
        }
        return $qb->getQuery()->getResult();

    }

    public function updatestatebydatetime(EntityManagerInterface $entityManager, OutingRepository $outingRepository, StateRepository $stateRepository): Void
    {

        $now = new \DateTime('now');

        $outings = $outingRepository->findAll();
        foreach ($outings as $outing){
            $outingStart = $outing->getDateTimeStartOuting();
            if( $outing->getRegistrationDeadLine() < $now && $outing->getDateTimeStartOuting() > $now){
                $outing->setState($stateRepository->find(3));
            }
            if($outing->getDateTimeStartOuting() < $now && $outingStart->add(new \DateInterval('PT' . $outing->getDuration() . 'M')) > $now){
                $outing->setState($stateRepository->find(4));
            }

            if($outing->getDateTimeStartOuting() < $now &&  $outing->getState()->getId() == 4){
                $outing->setState($stateRepository->find(5));
            }

            if($outingStart->add(new \DateInterval('P1M')) < $now){
                $outing->setState($stateRepository->find(7));
            }
            $entityManager->persist($outing);
            $entityManager->flush();
        }
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
