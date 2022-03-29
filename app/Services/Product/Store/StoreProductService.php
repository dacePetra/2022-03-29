<?php

namespace App\Services\Product\Store;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\MySqlProductRepository;

class StoreProductService
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new MySqlProductRepository();
    }

    public function execute(StoreProductRequest $request): void
    {
        $product = new Product(
            $request->getName(),
            $request->getDescription(),
            $request->getPrice(),
            $request->getAvailableAmount()
        );

        $this->productRepository->save($product);
    }

}