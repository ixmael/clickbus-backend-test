<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Transaction\AbstractTransaction;
use App\Entity\Transaction\CreditTransaction;
use App\Entity\Transaction\DebitTransaction;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    private $em;
    private $userRepository;
    private $accountRepository;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em,  UserRepository $userRepository, AccountRepository $accountRepository)
    {
        parent::__construct($registry, AbstractTransaction::class);
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->accountRepository = $accountRepository;
    }

    public function getAll()
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult()
        ;
    }

    public function exists($id)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function get($id)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function add(array $data)
    {
        
    }

    public function update($id, array $data)
    {
        
    }

    public function del($id)
    {
        $user = $this->exists($id);

        if ($user)
        {
            $this->em->remove($user);
            $this->em->flush();

            return true;
        }

        return false;
    }
}
