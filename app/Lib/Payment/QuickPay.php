<?php
#include(ROOT . DS . 'vendors' . DS . 'QuickPay' . DS . 'QuickPay.php');

class QuickPay {
    protected $mc_token = 'ddd31ae0bd74f0cbf96f104bc152cee7a44ec0e0292568089a90b5667f19df38';

    protected $client ;

    protected $user_token;
    protected $appkey;

    protected $currency = 'USD';

    protected $order_id;

    function __construct($mc_token, $appkey, $user_token)
    {
        $this->mc_token = $mc_token;
        $this->appkey = $appkey;
        $this->user_token = $user_token;

        $this->client = new \QuickPay\QuickPay(':' . $mc_token);
    }

    /**
     * @return string
     */
    public function getMcToken()
    {
        return $this->mc_token;
    }

    /**
     * @return \QuickPay\QuickPay
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return mixed
     */
    public function getUserToken()
    {
        return $this->user_token;
    }

    /**
     * @return mixed
     */
    public function getAppkey()
    {
        return $this->appkey;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param mixed $order_id
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }


    # tạo giao dịch
    # trả về link redirect
    # với loại thanh toán visa debit có 3 bank_code: VISA, MASTER
    public function visa( $amount, $buyer = array()){
        $return_url = Configure::read('QuickPay.ReturnUrl')
            . '?app=' . $this->getAppkey()
            . '&qtoken='. $this->getUserToken()
            . '&order_id=' . $this->getOrderId();

        $cancel_url = Configure::read('QuickPay.CancelUrl')
            . '?app=' . $this->getAppkey()
            . '&qtoken='. $this->getUserToken()
            . '&order_id=' . $this->getOrderId();

        $params = array(
            'order_id'      => (string) $this->getOrderId(), //Mã hóa đơn do website bán hàng sinh ra
            'currency'      => $this->getCurrency(),
            'callbackurl'	=> $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
            'cancelUrl'	    => $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
            'customer_email'    => $buyer['customer_email'], //Địa chỉ Email người mua
//            'customer_name'		=> $buyer['customer_name'], //Tên người mua hàng
//            'customer_phone'    => $buyer['customer_phone'], //Điện thoại người mua
        );


        $payment = $this->getClient()->request->post("/payments", $params)->asArray();
        if( empty($payment['id']) ){
            CakeLog::error('QuickPay initial error', 'payment');
            return false;
        }

        $QuickLink = $this->getClient()->request->put("/payments/" . $payment['id'] . "/link", [
            'amount' => $amount * 100
        ])->asArray();

        CakeLog::info('create order quickpay:' . print_r( $QuickLink, true), 'payment');
        if( empty($QuickLink['url']) ){
            CakeLog::error('QuickPay url error', 'payment');
            return false;
        }

        $QuickLink['id'] = $payment['id'];

        return $QuickLink;
    }

    public function getTransactionDetail(){
        $result = $this->getClient()->request->get("/payments/" . $this->getOrderId() )->asArray();

        CakeLog::info('get transaction quickpay:' . print_r( $result, true), 'payment');
        if ( !empty($result['accepted']) ){
            return true;
        }
        return false;
    }
}