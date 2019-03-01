<?php

namespace App\Tests\Util;

use PHPUnit\Framework\TestCase;

use App\Entity\Account\CreditAccount;
use App\Util\Calculator\CreditCalculator;

class CreditCalculatorTest extends TestCase
{
    public function testWithdraw() {
        $creditAccount = new CreditAccount();
        $creditAccount->setCredit(1000);

        $calculator = new CreditCalculator($creditAccount);
        $result = $calculator->getTotal(500);

        $this->assertEquals(550, $result);
    }

    public function testCanWithdraw() {
        $creditAccount = new CreditAccount();
        $creditAccount->setCredit(1000);

        $calculator = new CreditCalculator($creditAccount);
        $result = $calculator->canWithdraw(500);

        $this->assertEquals(true, $result);
    }

    public function testCannotWithdraw() {
        $creditAccount = new CreditAccount();
        $creditAccount->setCredit(1000);

        $calculator = new CreditCalculator($creditAccount);
        $result = $calculator->canWithdraw(950);

        $this->assertEquals(false, $result);
    }

    public function testCanPay() {
        $creditAccount = new CreditAccount();
        $creditAccount->setCredit(1000);
        $creditAccount->setLimitCredit(2000);

        $calculator = new CreditCalculator($creditAccount);
        $result = $calculator->canPay(500);

        $this->assertEquals(true, $result);
    }

    public function testCannotPay() {
        $creditAccount = new CreditAccount();
        $creditAccount->setCredit(1000);
        $creditAccount->setLimitCredit(2000);

        $calculator = new CreditCalculator($creditAccount);
        $result = $calculator->canPay(2000);

        $this->assertEquals(false, $result);
    }
}