<?php

namespace App\Services\Product\Store;

class StoreProductRequest
{
    private string $name;
    private string $description;
    private float $price;
    private int $availableAmount;

    public function __construct(string $name, string $description, float $price, int $availableAmount)
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->availableAmount = $availableAmount;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getAvailableAmount(): int
    {
        return $this->availableAmount;
    }

}