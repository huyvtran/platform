<?php
class Paypal {
    protected $paypal;
    protected $user_token;
    protected $appkey;

    function __construct($appkey, $user_token)
    {
        $this->appkey = $appkey;
        $this->user_token = $user_token;
        $this->paypal = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                Configure::read('Paypal.clientId'),
                Configure::read('Paypal.secret')
            )
        );
    }

    # tạo giao dịch
    # trả về link redirect paypal
    public function buy($nameProduct, $price, $currency = 'USD', $shipping = 0.00, $description = ''){
        $price_total = $price + $shipping;

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('Paypal');

        $item = new \PayPal\Api\Item();
        $item->setName($nameProduct)
            ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($price);

        $itemList = new \PayPal\Api\ItemList();
        $itemList->setItems([$item]);

        $detail = new \PayPal\Api\Details();
        $detail->setShipping($shipping)
            ->setSubtotal($price);

        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency($currency)
            ->setTotal($price_total)
            ->setDetails($detail);

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($description)
            ->setInvoiceNumber(uniqid());

        $redirectUrl = new \PayPal\Api\RedirectUrls();
        $redirectUrl->setReturnUrl(Configure::read('Paypal.ReturnUrl') . '?app=' . $this->appkey . '&qtoken=' . $this->user_token )
            ->setCancelUrl(Configure::read('Paypal.CancelUrl' ));

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrl)
            ->setTransactions([$transaction]);

        try{
            $payment->create($this->paypal);
        }catch (Exception $e){
            CakeLog::error('không tạo được giao dịch paypal', 'payment');
            return false;
        }

        $linkPaypal = $payment->getApprovalLink();
        if( !empty($linkPaypal) ) return $linkPaypal;
        return false;
    }
}