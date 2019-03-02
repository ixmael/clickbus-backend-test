<?php

namespace App\Entity\Account;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */
class DebitAccount extends AbstractAccount implements iAccount
{
    /**
     * @ORM\Column(type="decimal", precision=32, scale=2)
     * @Groups({"basic"})
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", precision=32, scale=2)
     */
    private $current_amount;

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getKind() {
        return parent::DEBIT_KIND;
    }

    public function getCurrentAmount()
    {
        return $this->current_amount;
    }

    public function setCurrentAmount($current_amount): self
    {
        $this->current_amount = $current_amount;

        return $this;
    }

    public function add($amount)
    {
        $this->setCurrentAmount($this->current_amount + $amount);
    }

    public function substract($amount)
    {
        $this->setCurrentAmount($this->current_amount - $amount);
    }
}
