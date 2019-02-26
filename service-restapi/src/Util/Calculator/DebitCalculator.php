<?php

namespace App\Util\Calculator;

use App\Entity\Account\iAccount;

class DebitCalculator implements iCalculator {
    public function __construct(iAccount $account) {
        $this->account = $account;
    }

    public function getTotal($amount) {
        $amountItems = [];

        // Amount to withdraw
        $amountItems[] = ['amount', $amount];

        return array_reduce($amountItems, function($carry, $item) {
            return $carry + $item[1];
        });
    }

    public function canWithdraw($amount) {
        $totalToWithdraw = $this->getTotal($amount);
        if ($this->account->getAmount() - $totalToWithdraw >= 0) {
            return true;
        }

        return false;
    }
}
