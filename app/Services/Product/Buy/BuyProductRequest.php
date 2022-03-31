<?php

namespace App\Services\Product\Buy;

class BuyProductRequest
{
    private int $productId;
    private int $productAmount;
    private string $selectedPaymentMethod;
    private float $amountPayable;
    private array $paymentInfo;

    public function __construct(int $productId, int $productAmount, string $selectedPaymentMethod, float $amountPayable, array $paymentInfo)
    {
        $this->productId = $productId;
        $this->productAmount = $productAmount;
        $this->selectedPaymentMethod = $selectedPaymentMethod;
        $this->amountPayable = $amountPayable;
        $this->paymentInfo = $paymentInfo;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getProductAmount(): int
    {
        return $this->productAmount;
    }

    public function getSelectedPaymentMethod(): string
    {
        return $this->selectedPaymentMethod;
    }

    public function getAmountPayable(): float
    {
        return $this->amountPayable;
    }

    public function getPaymentInfo(): array
    {
        return $this->paymentInfo;
    }

}