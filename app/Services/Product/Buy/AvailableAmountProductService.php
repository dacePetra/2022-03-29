<?php

namespace App\Services\Product\Buy;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\MySqlProductRepository;

class AvailableAmountProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(int $productId): int
    {
        return $this->productRepository->getAvailableAmountById($productId);
    }

}