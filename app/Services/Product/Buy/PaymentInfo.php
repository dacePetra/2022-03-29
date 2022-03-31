<?php

namespace App\Services\Product\Buy;

interface PaymentInfo
{
    public function getPaymentInfo():array;
}