<?php

namespace App\Services\Product\Buy;

use App\Repositories\Product\ProductRepository;

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