<?php

namespace App\Models;

class Product
{
    private string $name;
    private string $description;
    private float $price;
    private int $availableAmount;
    private ?int $id = null;
    private ?string $addedAt = null;


    public function __construct(string $name, string $description, float $price, int $availableAmount, ?int $id = null, string $addedAt = null)
    {
        $this->name = $name;
        $this->description = $description;

        $this->price = $price;
        $this->availableAmount = $availableAmount;
        $this->addedAt = $addedAt;
        $this->id = $id;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddedAt(): string
    {
        return $this->addedAt;
    }
}