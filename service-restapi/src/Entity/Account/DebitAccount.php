<?php

namespace App\Entity\Account;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DebitAccount extends AbstractAccount implements iAccount
{
    /**
     * @ORM\Column(type="decimal", precision=32, scale=2)
     */
    private $amount;

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
