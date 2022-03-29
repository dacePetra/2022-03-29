<?php

namespace App\Repositories\Product;

use App\Models\Product;

interface ProductRepository
{
    public function save(Product $product): void;

    public function getProducts(): array;

    public function getProductById(int $productId): Product;

    public function buy(int $productId, int $availableAmountAfterPurchase): void;

    public function getAvailableAmountById($productId): int;
}