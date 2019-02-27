<?php

namespace App\Util\Calculator;

use App\Entity\Account\iAccount;

class CreditCalculator implements iCalculator
{
    public function __construct(iAccount $account)
    {
        $this->account = $account;
    }

    public function getTotal($amount)
    {
        $amountItems = [];

        // Amount to withdraw
        $amountItems[] = ['amount', $amount];

        // Comission of the amount
        $commission = ($amount * getenv('CREDIT_COMMISSION_PERCENTAGE')) / 100;
        $amountItems[] = ['commision', $commission];

        return array_reduce($amountItems, function($carry, $item) {
            return $carry + $item[1];
        });
    }

    public function canWithdraw($amount)
    {
        $totalToWithdraw = $this->getTotal($amount);
        if ($this->account->getCredit() - $totalToWithdraw >= 0)
        {
            return true;
        }

        return false;
    }
}
