<?php

namespace App\Entity\Transaction;

use App\Entity\Account\AbstractAccount;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="transactions")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="account_kind", type="string")
 * @ORM\DiscriminatorMap({ "debit" = "DebitTransaction", "credit" = "CreditTransaction" })
 */
abstract class AbstractTransaction
{
    const WITHDRAW = 'withdraw';
    const PAY      = 'pay';
    const COMMISSION = 'commission';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $kind;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\AbstractAccount", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="guid")
     */
    private $guid;

    /**
     * @ORM\Column(type="decimal", precision=32, scale=2)
     */
    private $amount;

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(string $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?AbstractAccount
    {
        return $this->account;
    }

    public function setAccount(?AbstractAccount $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
