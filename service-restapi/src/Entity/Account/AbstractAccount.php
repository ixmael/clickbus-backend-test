<?php

namespace App\Entity\Account;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;
use App\Entity\Transaction\AbstractTransaction;

/**
 * @ORM\Entity
 * @ORM\Table(name="accounts")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="account_kind", type="string")
 * @ORM\DiscriminatorMap({ "debit" = "DebitAccount", "credit" = "CreditAccount" })
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractAccount
{
    const CREDIT_KIND = 'credit';
    const DEBIT_KIND  = 'debit';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"basic"})
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\AbstractTransaction", mappedBy="account")
     */
    private $transactions;

    /**
     * @Groups({"basic"})
     */
    abstract public function getKind();

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    /**
     * @Groups({"basic"})
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups({"basic"})
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @Groups({"basic"})
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @Groups({"basic"})
     */
    public function getUserId()
    {
        return $this->user->getId();
    }

    /**
     * @return Collection|AbstractTransaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(AbstractTransaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setAccount($this);
        }

        return $this;
    }

    public function removeTransaction(AbstractTransaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getAccount() === $this) {
                $transaction->setAccount(null);
            }
        }

        return $this;
    }
}
