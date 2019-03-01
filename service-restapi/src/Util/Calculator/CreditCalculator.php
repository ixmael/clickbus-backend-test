<?php

namespace App\Util\Calculator;

use App\Entity\Account\iAccount;

class CreditCalculator implements iCalculator
{
    public function __construct(iAccount $account)
    {
        $this->account = $account;
        $this->items = [];
    }

    public function getTotal($amount)
    {
        if (count($this->items) === 0)
        {
            // Amount to withdraw
            $this->items['amount'] = $amount;

            // Comission of the amount
            $commission = ($amount * getenv('CREDIT_COMMISSION_PERCENTAGE')) / 100;
            $this->items['commision'] = $commission;
        }

        $total = 0;
        foreach ($this->items as $v)
        {
            $total += $v;
        }

        return $total;
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

    public function getItems()
    {
        return $this->items;
    }

    public function canPay($amount)
    {
        if ($this->account->getCredit() + $amount <= $this->account->getLimitCredit())
        {
            return true;
        }

        return false;
    }
}
