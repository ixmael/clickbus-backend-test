<?php

namespace App\Tests\Util;

use PHPUnit\Framework\TestCase;

use App\Entity\Account\DebitAccount;
use App\Util\Calculator\DebitCalculator;

class DebitCalculatorTest extends TestCase
{
    public function testWithdraw() {
        $creditAccount = new DebitAccount();
        $creditAccount->setAmount(1000);

        $calculator = new DebitCalculator($creditAccount);
        $result = $calculator->getTotal(500);

        $this->assertEquals(500, $result);
    }

    public function testCanWithdraw() {
        $creditAccount = new DebitAccount();
        $creditAccount->setAmount(1000);

        $calculator = new DebitCalculator($creditAccount);
        $result = $calculator->canWithdraw(500);

        $this->assertEquals(true, $result);
    }

    public function testCannotWithdraw() {
        $creditAccount = new DebitAccount();
        $creditAccount->setAmount(1000);

        $calculator = new DebitCalculator($creditAccount);
        $result = $calculator->canWithdraw(1001);

        $this->assertEquals(false, $result);
    }
}