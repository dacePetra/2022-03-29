<?php

namespace App\Services\Product\Buy;

class CreditCardPaymentMethod implements PaymentMethod
{
    private string $name;
    private string $number;
    private string $cvc;

    public function __construct(string $name, string $number, string $cvc)
    {
        $this->name = $name;
        $this->number = $number;
        $this->cvc = $cvc;
    }


    public function pay(float $amountPayable): string
    {
        return $amountPayable . " EUR paid with credit card, name:" . $this->name . ", number:" . $this->number . ", cvc:" . $this->cvc;
    }


}