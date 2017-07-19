<?php
class NL {
    protected $user_token;
    protected $appkey;

    protected $order_id;

    function __construct($appkey, $user_token)
    {
        $this->appkey = $appkey;
        $this->user_token = $user_token;
    }

    public function setOrderId($order_id){
        $this->order_id = $order_id;
    }

    public function getOrderId(){
        return $this->order_id;
    }

    # tạo giao dịch
    # trả về link redirect paypal
    public function buy($nameProduct, $price, $currency = 'USD', $shipping = 0.00, $description = ''){
        return false;
    }
}