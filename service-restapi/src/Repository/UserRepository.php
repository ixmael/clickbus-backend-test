<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements iAppRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, User::class);
        $this->em = $em;
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
            ->getOneOrNullResult();
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
        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function update($id, array $data)
    {
        $user = $this->exists($id);
        if ($user)
        {
            if (\array_key_exists('name', $data))
            {
                $user->setName($data['name']);
            }

            if (\array_key_exists('email', $data))
            {
                $user->setEmail($data['email']);
            }

            $this->em->persist($user);
            $this->em->flush();

            return $user;
        }

        return null;
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
