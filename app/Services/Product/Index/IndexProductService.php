<?php

namespace App\Services\Product\Index;

use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\MySqlProductRepository;

class IndexProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(): array
    {
        return $this->productRepository->getProducts();
    }

}