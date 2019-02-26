<?php

namespace App\Entity\Account;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CreditAccount extends AbstractAccount implements iAccount
{
    /**
     * @ORM\Column(type="decimal", precision=32, scale=2)
     */
    private $credit;

    public function getCredit()
    {
        return $this->credit;
    }

    public function setCredit($credit): self
    {
        $this->credit = $credit;

        return $this;
    }
}
