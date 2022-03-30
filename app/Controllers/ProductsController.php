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
use Psr\Container\ContainerInterface;

class ProductsController
{
//    Šādi ir tad, ja dod tālāk visu container.
    private ContainerInterface $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

//  Ja nedod visu container tālāk, tad šādi?
//    private StoreProductService $storeProductService;
//
//    public function __construct(StoreProductService $storeProductService)
//    {
//        $this->storeProductService = $storeProductService;
//    }

    public function index(): View
    {
        $service = $this->container->get(IndexProductService::class);
        $products = $service->execute();

        $purchaseConfirmed = "";
        if (isset($_SESSION["purchaseConfirmed"])) {
            $purchaseConfirmed = $_SESSION["purchaseConfirmed"];
            unset($_SESSION["purchaseConfirmed"]);
        }

        return new View('Products/index', [
            'products' => $products,
            'purchaseConfirmed' => $purchaseConfirmed
        ]);
    }

    public function show(array $vars): View
    {
        $productId = (int)$vars['id'];
        $service = $this->container->get(ShowProductService::class);
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

        $service = $this->container->get(StoreProductService::class);
        $service->execute($request);
//        Ja nebūtu padots viss container, tad būtu šādi
//        $this->storeProductService->execute($request);

        return new Redirect('/products');
    }

    public function buy(array $vars)
    {
        // TODO Validation for input(amount)
        $productId = (int)$vars['id'];
        $amount = $_POST['amount'];

        $service = $this->container->get(AvailableAmountProductService::class);
        $availableAmount = $service->execute($productId);

        if ($amount > $availableAmount) {
            $_SESSION["amountNotAvailable"] = "The chosen amount is not available";
            return new Redirect("/products/$productId");
        }

        $service = $this->container->get(ShowProductService::class);
        $product = $service->execute($productId);
        $total = $product->getPrice() * $amount;

        return new View('Products/buy', [
            'product' => $product,
            'amount' => $amount,
            'total' => $total
        ]);
    }

    public function confirmed(array $vars): Redirect
    {
        $amount = (int) $_POST['amount'];
        $productId = (int) $_POST['productId'];

        $service = $this->container->get(AvailableAmountProductService::class);
        $availableAmount = $service->execute($productId);

        $availableAmountAfterPurchase = $availableAmount - $amount;
        $service = $this->container->get(BuyProductService::class);
        $service->execute($productId, $availableAmountAfterPurchase);

        $_SESSION["purchaseConfirmed"] = "Purchase confirmed!";
        return new Redirect('/products');
    }
}