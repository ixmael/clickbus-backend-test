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
}
