<?php

namespace App\Repositories\Product;

use App\Database;
use App\Models\Product;

class CsvProductRepository implements ProductRepository
{
    public function save(Product $product): void
    {
        var_dump("save in CSV");die;
    }

    public function getProducts(): array
    {
        var_dump("save in CSV");die;
        return $products;
    }

}