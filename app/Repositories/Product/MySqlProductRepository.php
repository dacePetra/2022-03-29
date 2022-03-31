<?php

namespace App\Repositories\Product;

use App\Database;
use App\Models\Product;

class MySqlProductRepository implements ProductRepository
{
    public function save(Product $product): void
    {
        Database::connection()
            ->insert('products', [
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'available_amount' => $product->getAvailableAmount(),
            ]);
    }

    public function getProducts(): array
    {
        $productsQuery = Database::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('products')
            ->executeQuery()
            ->fetchAllAssociative();

        $products = [];
        foreach ($productsQuery as $productData) {
            $products [] = new Product(
                $productData['name'],
                $productData['description'],
                $productData['price'],
                $productData['available_amount'],
                $productData['id'],
                $productData['added_at']
            );
        }
        return $products;
    }

    public function getProductById(int $productId): Product
    {
        $productQuery = Database::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('products')
            ->where('id = ?')
            ->setParameter(0, $productId)
            ->executeQuery()
            ->fetchAssociative();

        return new Product(
            $productQuery['name'],
            $productQuery['description'],
            $productQuery['price'],
            $productQuery['available_amount'],
            $productQuery['id'],
            $productQuery['added_at']
        );
    }

    public function getAvailableAmountById($productId): int
    {
        $productQuery = Database::connection()
            ->createQueryBuilder()
            ->select('available_amount')
            ->from('products')
            ->where('id = ?')
            ->setParameter(0, $productId)
            ->executeQuery()
            ->fetchAssociative();

        return (int) $productQuery["available_amount"];
    }

    public function buy(int $productId, int $productAmount): void
    {
        $productQuery = Database::connection()
            ->createQueryBuilder()
            ->select('available_amount')
            ->from('products')
            ->where('id = ?')
            ->setParameter(0, $productId)
            ->executeQuery()
            ->fetchAssociative();

        Database::connection()
            ->update('products', [
                'available_amount' => ((int)$productQuery["available_amount"]-$productAmount),
            ], ['id' => $productId]
            );

    }

}