<?php

namespace App\Services\Product\Buy;

class PaypalPaymentMethod implements PaymentMethod
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function pay(float $amountPayable): string
    {
        return $amountPayable . " EUR paid with PayPal, email: " . $this->email;
    }

}