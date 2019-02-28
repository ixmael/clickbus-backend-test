<?php

namespace App\Entity\Account;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */
class CreditAccount extends AbstractAccount implements iAccount
{
    /**
     * @ORM\Column(type="decimal", precision=32, scale=2)
     * @Groups({"basic"})
     */
    private $credit;

    /**
     * @ORM\Column(type="decimal", precision=32, scale=2)
     */
    private $limit_credit;

    public function getCredit()
    {
        return $this->credit;
    }

    public function setCredit($credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    public function getKind() {
        return parent::CREDIT_KIND;
    }

    public function getLimitCredit()
    {
        return $this->limit_credit;
    }

    public function setLimitCredit($limit_credit): self
    {
        $this->limit_credit = $limit_credit;

        return $this;
    }
}
