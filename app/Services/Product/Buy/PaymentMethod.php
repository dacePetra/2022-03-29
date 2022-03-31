<?php

namespace App\Services\Product\Buy;

interface PaymentMethod
{
    public function pay(float $amountPayable):string;
}