<?php

namespace App\Services\Product\Buy;

class CreditCardPaymentInfo implements PaymentInfo
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

    public function getPaymentInfo(): array
    {
        return [
            'name' => $this->name,
            'number' => $this->number,
            'cvc' => $this->cvc
        ];
    }

}