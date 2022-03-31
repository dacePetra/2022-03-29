<?php

namespace App\Services\Product\Buy;

class PaymentInfoProcessor
{
    private PaymentInfo $paymentInfo;

    public function __construct(PaymentInfo $paymentInfo)
    {
        $this->paymentInfo = $paymentInfo;
    }

    public function handle(): array
    {
        return $this->paymentInfo->getPaymentInfo();
    }
}