<?php

namespace App\Services\Product\Show;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\MySqlProductRepository;

class ShowProductService
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new MySqlProductRepository();
    }

    public function execute(int $productId): Product
    {
        return $this->productRepository->getProductById($productId);
    }

}