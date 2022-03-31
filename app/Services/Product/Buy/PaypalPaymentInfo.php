<?php

namespace App\Services\Product\Buy;

class PaypalPaymentInfo implements PaymentInfo
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getPaymentInfo(): array
    {
        return ['email' => $this->email];
    }


}