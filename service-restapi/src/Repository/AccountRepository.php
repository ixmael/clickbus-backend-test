<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Account\AbstractAccount;
use App\Entity\Account\CreditAccount;
use App\Entity\Account\DebitAccount;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    private $em;
    private $userRepository;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em,  UserRepository $userRepository)
    {
        parent::__construct($registry, AbstractAccount::class);
        $this->em = $em;
        $this->userRepository = $userRepository;
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
            ->getOneOrNullResult();
    }

    public function add(array $data)
    {
        $user = $this->userRepository->get($data['user_id']);
        if ($user)
        {
            $account = null;
            if ($data['account_kind'] === AbstractAccount::CREDIT_KIND)
            {
                $account = new CreditAccount();
                $account->setCredit($data['credit']);
                $account->setLimitCredit($data['limit_credit']);
            }
            else
            {
                $account = new DebitAccount();
                $account->setAmount($data['amount']);
                $account->setCurrentAmount($data['amount']);
            }
            $account->setUser($user);

            $this->em->persist($account);
            $this->em->flush();

            return $account;
        }

        throw new Exception('The users not exists');
    }

    public function update($id, array $data)
    {
        $account = $this->exists($id);
        if ($account)
        {
            $updateElements = [];
            $params = [];

            if (\array_key_exists('account_kind', $data) && $data['account_kind'] !== $account->getKind())
            {
                $updateElements[] = 'account_kind= :kind';

                if ($data['account_kind'] === 'credit')
                {
                    $updateElements[] = 'amount = NULL';
                    $updateElements[] = 'credit = :credit';
                    $updateElements[] = 'limit_credit = :limit_credit';

                    $params['kind'] = AbstractAccount::CREDIT_KIND;
                    $params['credit'] = $data['credit'];
                    $params['limit_credit'] = $data['limit_credit'];
                }
                elseif ($data['account_kind'] === 'debit')
                {
                    $updateElements[] = 'amount = :amount';
                    $updateElements[] = 'credit = NULL';
                    $updateElements[] = 'limit_credit = NULL';

                    $params['kind'] = AbstractAccount::DEBIT_KIND;
                    $params['amount'] = $data['amount'];
                }
            }

            if (\array_key_exists('user_id', $data))
            {
                $user = $this->userRepository->get($data['user_id']);

                if (!$user)
                {
                    throw new Exception('The user not exists');
                }

                $updateElements[] = 'user_id = :userId';
                $params['userId'] = $user->getId();
            }

            $query = 'UPDATE ' .
                $this->em->getClassMetadata('App:Account\\AbstractAccount')->getTableName() .
                ' SET ' .
                join(',', $updateElements) .
                ' WHERE `accounts`.`id` = ' . $account->getId() . ';';

            if (count($updateElements) > 0)
            {
                $updateQuery = $this->em->getConnection()->prepare($query);
                $updateQuery->execute($params);
            }

            return $account;
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
