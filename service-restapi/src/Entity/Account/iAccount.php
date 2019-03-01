<?php

namespace App\Entity\Account;

interface iAccount
{
    public function add($amount);
    public function substract($amount);
    public function getCurrentAmount();
}
