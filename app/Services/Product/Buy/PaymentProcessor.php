<?php

namespace App\Services\Product\Buy;

class PaymentProcessor
{
    private PaymentMethod $paymentMethod;

    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function handle($amountPayable): string
    {
        return $this->paymentMethod->pay($amountPayable);
    }


}