<?php

namespace App\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DebitTransaction extends AbstractTransaction implements iTransaction
{
}
