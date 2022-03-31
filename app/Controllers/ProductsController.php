<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\Product\Buy\AvailableAmountProductService;
use App\Services\Product\Buy\BuyProductRequest;
use App\Services\Product\Buy\BuyProductService;
use App\Services\Product\Buy\CreditCardPaymentInfo;
use App\Services\Product\Buy\PaymentInfoProcessor;
use App\Services\Product\Buy\PaypalPaymentInfo;
use App\Services\Product\Index\IndexProductService;
use App\Services\Product\Show\ShowProductService;
use App\Services\Product\Store\StoreProductRequest;
use App\Services\Product\Store\StoreProductService;
use App\Views\View;


class ProductsController
{
    private StoreProductService $storeProductService;
    private BuyProductService $buyProductService;
    private IndexProductService $indexProductService;
    private ShowProductService $showProductService;
    private AvailableAmountProductService $availableAmountProductService;

    public function __construct(StoreProductService $storeProductService, BuyProductService $buyProductService, IndexProductService $indexProductService, ShowProductService $showProductService, AvailableAmountProductService $availableAmountProductService)
    {
        $this->storeProductService = $storeProductService;
        $this->buyProductService = $buyProductService;
        $this->indexProductService = $indexProductService;
        $this->showProductService = $showProductService;
        $this->availableAmountProductService = $availableAmountProductService;
    }

    public function index(): View
    {
        $products = $this->indexProductService->execute();

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

        $product = $this->showProductService->execute($productId);

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
        $this->storeProductService->execute($request);

        return new Redirect('/products');
    }

    public function buy(array $vars)
    {
        // TODO Validation for input(amount)
        $productId = (int)$vars['id'];
        $amount = $_POST['amount'];

        $availableAmount = $this->availableAmountProductService->execute($productId);

        if ($amount > $availableAmount) {
            $_SESSION["amountNotAvailable"] = "The chosen amount is not available";
            return new Redirect("/products/$productId");
        }

        $product = $this->showProductService->execute($productId);
        $total = $product->getPrice() * $amount;

        return new View('Products/buy', [
            'product' => $product,
            'amount' => $amount,
            'total' => $total
        ]);
    }

    public function confirm(array $vars): Redirect
    {
        $productId = (int)$_POST['productId'];
        $productAmount = (int)$_POST['productAmount'];
        $amountPayable = (int)$_POST['productAmount'] * (int)$_POST['productPrice'];
        $selectedPaymentMethod = $_POST['exampleRadios'];

//      TODO validation of payment information details

        switch ($selectedPaymentMethod) {
            case 'paypal':
                $paymentInfo = new PaypalPaymentInfo($_POST['email']);
                break;
            case 'card':
                $paymentInfo = new CreditCardPaymentInfo($_POST['name'], $_POST['number'], $_POST['cvc']);
                break;
            default:
                // TODO throw new Exception
                break;
        }

        $paymentInfo = (new PaymentInfoProcessor($paymentInfo))->handle();

        $request = new BuyProductRequest($productId, $productAmount, $selectedPaymentMethod, $amountPayable, $paymentInfo);
        $message = $this->buyProductService->execute($request);

        $_SESSION["purchaseConfirmed"] = "Purchase confirmed! " . $message;

        return new Redirect('/products');
    }
}