<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\Product\Buy\AvailableAmountProductService;
use App\Services\Product\Buy\BuyProductService;
use App\Services\Product\Index\IndexProductService;
use App\Services\Product\Show\ShowProductService;
use App\Services\Product\Store\StoreProductRequest;
use App\Services\Product\Store\StoreProductService;
use App\Views\View;

class ProductsController
{
    public function index(): View
    {
        $service = new IndexProductService();
        $products = $service->execute();

        return new View('Products/index', [
            'products' => $products
        ]);
    }

    public function show(array $vars): View
    {
        $productId = (int)$vars['id'];
        $service = new ShowProductService();
        $product = $service->execute($productId);

        $amountNotAvailable = "";
        if (isset($_SESSION["amountNotAvailable"])) {
            $amountNotAvailable = $_SESSION["amountNotAvailable"];
            unset($_SESSION["amountNotAvailable"]);
        }

        return new View('Products/show', [
            'product' => $product,
            'amountNotAvailable' => $amountNotAvailable
        ]);
    }

    public function add(array $vars): View
    {
        return new View('Products/add');
    }

    public function store(): Redirect
    {
        // TODO Validation: is price positive?
        // TODO Validation: is available amount positive?
        // TODO Validation: is name unique?

        // If price is entered with , then change it to .
        $price = (float)str_replace(",", ".", $_POST['price']);

        $request = new StoreProductRequest($_POST['name'], $_POST['description'], $price, $_POST['available_amount']);
        $service = new StoreProductService();
        $service->execute($request);

        return new Redirect('/products');
    }

    public function buy(array $vars)
    {
        // TODO Validation for input(amount)
        $productId = (int)$vars['id'];
        $amount = $_POST['amount'];

        $service = new AvailableAmountProductService();
        $availableAmount = $service->execute($productId);

        if ($amount > $availableAmount) {
            $_SESSION["amountNotAvailable"] = "The chosen amount is not available";
            return new Redirect("/products/$productId");
        }

        $service = new ShowProductService();
        $product = $service->execute($productId);
        $total = $product->getPrice() * $amount;

        return new View('Products/buy', [
            'product' => $product,
            'amount' => $amount,
            'total' => $total
        ]);
    }

    public function confirmed(array $vars): View
    {
        $amount = (int) $_POST['amount'];
        $productId = (int) $_POST['productId'];

        $service = new AvailableAmountProductService();
        $availableAmount = $service->execute($productId);

        $availableAmountAfterPurchase = $availableAmount - $amount;
        $service = new BuyProductService();
        $service->execute($productId, $availableAmountAfterPurchase);

        return new View('Products/confirmed');
    }
}