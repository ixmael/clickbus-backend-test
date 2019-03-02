<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

use App\Entity\Account\AbstractAccount;
use App\Entity\Transaction\AbstractTransaction;
use App\Entity\Transaction\CreditTransaction;
use App\Entity\Transaction\DebitTransaction;
use App\Util\Calculator\DebitCalculator;
use App\Util\Calculator\CreditCalculator;

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
        $account = $this->accountRepository->get($data['account_id']);

        if (!$account)
        {
            throw new Exception('The account not exists');
        }

        $transaction = null;
        if ($data['transaction_kind'] === AbstractTransaction::WITHDRAW)
        {
            if ($account->getKind() === AbstractAccount::DEBIT_KIND)
            {
                $calculator = new DebitCalculator($account);
                $withdrawAmount = $calculator->getTotal($data['amount']);

                if ($calculator->canWithdraw($data['amount']))
                {
                    $guid = Uuid::uuid4();

                    $account->setCurrentAmount($account->getCurrentAmount() - $withdrawAmount);
                    $transaction = new DebitTransaction();
                    $transaction->setAccount($account);
                    $transaction->setAmount($withdrawAmount);
                    $transaction->setGuid($guid->toString());
                    $transaction->setKind(AbstractTransaction::WITHDRAW);

                    $this->em->persist($account);
                    $this->em->persist($transaction);
                    $this->em->flush();
                }
                else
                {
                    throw new \Exception('The amount withdraw exceeds the current amount of the account');
                }
            }
            else if ($account->getKind() === AbstractAccount::CREDIT_KIND)
            {
                $calculator = new CreditCalculator($account);
                $withdrawAmount = $calculator->getTotal($data['amount']);

                if ($calculator->canWithdraw($data['amount']))
                {
                    $guid = Uuid::uuid4();

                    $account->setCredit($account->getCredit() - $withdrawAmount);
                    $transaction = new CreditTransaction();
                    $transaction->setAccount($account);
                    $transaction->setAmount($data['amount']);
                    $transaction->setGuid($guid->toString());
                    $transaction->setKind(AbstractTransaction::WITHDRAW);

                    $commission = new CreditTransaction();
                    $commission->setAccount($account);
                    $commission->setAmount($calculator->getItems()['commision']);
                    $commission->setGuid($guid->toString());
                    $commission->setKind(AbstractTransaction::COMMISSION);

                    $this->em->persist($account);
                    $this->em->persist($transaction);
                    $this->em->persist($commission);
                    $this->em->flush();
                }
                else
                {
                    throw new \Exception('The amount withdraw exceeds the current amount of the account');
                }
            }
        }
        else if ($data['transaction_kind'] === AbstractTransaction::PAY)
        {
            if ($account->getKind() === AbstractAccount::DEBIT_KIND)
            {
                $calculator = new DebitCalculator($account);

                if ($calculator->canPay($data['amount']))
                {
                    $guid = Uuid::uuid4();

                    $account->setCurrentAmount($account->getCurrentAmount() + $data['amount']);
                    $transaction = new DebitTransaction();
                    $transaction->setAccount($account);
                    $transaction->setAmount($data['amount']);
                    $transaction->setGuid($guid->toString());
                    $transaction->setKind(AbstractTransaction::WITHDRAW);

                    $this->em->persist($account);
                    $this->em->persist($transaction);
                    $this->em->flush();
                }
            }
            else if ($account->getKind() === AbstractAccount::CREDIT_KIND)
            {
                $calculator = new CreditCalculator($account);

                if ($calculator->canPay($data['amount']))
                {
                    $guid = Uuid::uuid4();

                    $account->setCredit($account->getCredit() + $data['amount']);
                    $transaction = new CreditTransaction();
                    $transaction->setAccount($account);
                    $transaction->setCredit($data['amount']);
                    $transaction->setGuid($guid->toString());
                    $transaction->setKind(AbstractTransaction::PAY);

                    $this->em->persist($account);
                    $this->em->persist($transaction);
                    $this->em->flush();
                }
                else
                {
                    throw new \Exception('The amount pay exceeds the credit limit of the account');
                }
            }
        }

        if ($transaction)
        {
            return $transaction;
        }

        throw new \Exception('Cannot process the data information');
    }

    public function update($id, array $data)
    {
        
    }

    public function del($id)
    {
        $transaction = $this->exists($id);

        if ($transaction)
        {
            $account = $transaction->getAccount();
            if ($transaction->getKind() === AbstractTransaction::WITHDRAW)
            {
                $account->add($transaction->getAmount());
            }
            elseif ($transaction->getKind() === AbstractTransaction::PAY)
            {
                $account->substract($transaction->getAmount());
            }

            $this->em->remove($transaction);
            $this->em->persist($account);
            $this->em->flush();

            return $account;
        }

        return false;
    }
}
