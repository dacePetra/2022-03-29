<?php

namespace App\Services\Product\Buy;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\MySqlProductRepository;

class BuyProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute($request): string
    {

        switch ($request->getSelectedPaymentMethod())
        {
            case 'paypal':
                $paymentMethod = new PaypalPaymentMethod($request->getPaymentInfo()['email']);
                break;
            case 'card':
                $paymentMethod = new CreditCardPaymentMethod($request->getPaymentInfo()['name'], $request->getPaymentInfo()['number'], $request->getPaymentInfo()['cvc']);
                break;
            default:
                //throw new Exception
                break;
        }
        $message = (new PaymentProcessor($paymentMethod))->handle($request->getAmountPayable());

        $this->productRepository->buy($request->getProductId(), $request->getProductAmount());
        return $message;
    }

}