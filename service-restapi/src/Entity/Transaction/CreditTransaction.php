<?php

namespace App\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CreditTransaction extends AbstractTransaction implements iTransaction
{
}
