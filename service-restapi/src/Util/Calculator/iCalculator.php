<?php

namespace App\Util\Calculator;

interface iCalculator
{
    public function getTotal($amount);
    public function canWithdraw($amount);
}
