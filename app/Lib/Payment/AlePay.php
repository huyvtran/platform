<?php
class AlePay {
    protected $mc_token = 'tqtLWMqnKkqi3NRP32amXwSxJuFOCL';
    protected $mc_checksum = 'Sj21QrpiNpI6DrFutfRWUetCwCK4CU';
    protected $mc_encrypt = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCIZlME8jWIGDQRmLQxmw/8Gd8vgcoHLPNoaAnmq8WKvQb2Tk6uI0wyOqOI2IHNZm/k5Wz6NQvsiFgLWTXhtpyvaMfAFLQzc9cYWy6yBd+56QGYiYIMJdsR1wIkBZLQ5UPQleVXrnyhs1NPnZVJU0BsRurmQiHFSi1mHqtiZUQ1RQIDAQAB';

    protected $user_token;
    protected $appkey;

    protected $checkout_type = 1; // chỉ thanh toán thường
    protected $currency = 'USD';

    protected $order_id;

    function __construct($mc_token, $mc_checksum, $mc_encrypt, $appkey, $user_token)
    {
        $this->mc_token = $mc_token;
        $this->mc_checksum = $mc_checksum;
        $this->mc_encrypt = $mc_encrypt;
        $this->appkey = $appkey;
        $this->user_token = $user_token;
    }

    /**
     * @return string
     */
    public function getMcToken()
    {
        return $this->mc_token;
    }

    /**
     * @return string
     */
    public function getMcChecksum()
    {
        return $this->mc_checksum;
    }

    /**
     * @return string
     */
    public function getMcEncrypt()
    {
        return $this->mc_encrypt;
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
     * @return int
     */
    public function getCheckoutType()
    {
        return $this->checkout_type;
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

    public function CheckoutCall($url, $post_field){
        $data_string = json_encode($post_field);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ( !empty($result) && $status==200){
            return json_decode($result, true);
        }
        return false;
    }

    public function GetErrorMessage($error_code) {
        $error_msg = 'Giao dịch không hợp lệ';
        $arrCode = array(
            '000'   => 'Thành công',
            '101'   => 'Checksum không hợp lệ',
            '102'   => 'Mã hóa không hợp lệ',
            '103'   => 'Địa chỉ IP truy cập bị từ chối',
            '104'   => 'Dữ liệu không hợp lệ',
            '105'   => 'Token key không hợp lệ',
            '106'   => 'Token thanh toán Alepay không tồn tại hoặc đã bị hủy',
            '107'   => 'Giao dịch đang được xử lý',
            '108'   => 'Dữ liệu không tìm thấy',
            '109'   => 'Mã đơn hàng không tìm thấy',
            '110'   => 'Phải có email hoặc số điện thoại người mua',
            '111'   => 'Giao dịch thất bại',
            '120'   => 'Giá trị đơn hàng phải lớn hơn 0',
            '121'   => 'Loại tiền tệ không hợp lệ',
            '122'   => 'Mô tả đơn hàng không tìm thấy',
            '134'   => 'Thẻ hết hạn mức thanh toán',
            '135'   => 'Giao dịch bị từ chối bởi ngân hàng phát hành thẻ',
        );
        if( !empty($arrCode[(string)$error_code]) ) $error_msg = $arrCode[(string)$error_code];

        return $error_msg;
    }

    public function encrypt($data){
        include(ROOT . DS . 'vendors' . DS . 'Rsa' . DS . 'index.php');

        $rsa = new Crypt_RSA();
        $rsa->loadKey($this->getMcEncrypt());
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $output = $rsa->encrypt($data);
        return base64_encode($output);
    }

    public function decrypt($data)
    {
        include(ROOT . DS . 'vendors' . DS . 'Rsa' . DS . 'index.php');

        $rsa = new Crypt_RSA();
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $ciphertext = base64_decode($data);
        $rsa->loadKey($this->getMcEncrypt());
        $output = $rsa->decrypt($ciphertext);
        return $output;
    }

    # tạo giao dịch
    # trả về link redirect
    # với loại thanh toán visa debit có 3 bank_code: VISA, MASTER
    public function visa( $amount, $buyer = array()){
        $return_url = Configure::read('Ale.ReturnUrl')
            . '?app=' . $this->getAppkey()
            . '&qtoken='. $this->getUserToken()
            . '&order_id=' . $this->getOrderId();
//        $return_url = urlencode($return_url);

        $cancel_url = Configure::read('Ale.CancelUrl')
            . '?app=' . $this->getAppkey()
            . '&qtoken='. $this->getUserToken()
            . '&order_id=' . $this->getOrderId();
//        $cancel_url = urlencode($cancel_url);

        $params = array(
            'orderCode'     => (string) $this->getOrderId(), //Mã hóa đơn do website bán hàng sinh ra
            'amount'	    => (double) $amount, //Tổng số tiền của hóa đơn
            'currency'      => $this->getCurrency(),
            'orderDescription'		=> 'Package ' . $amount, //Mô tả đơn hàng
            'totalItem'     => 1, // tổng sản phẩm
            'checkoutType'  => $this->getCheckoutType(),
            'returnUrl'	    => $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
            'cancelUrl'	    => $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
            'buyerName'		=> urlencode($buyer['buyer_name']), //Tên người mua hàng
            'buyerEmail'    => urlencode($buyer['buyer_email']), //Địa chỉ Email người mua
            'buyerPhone'    => urlencode($buyer['buyer_phone']), //Điện thoại người mua
            'buyerAddress'  => urlencode($buyer['buyer_address']), //Địa chỉ người mua hàng
            'buyerCity'     => urlencode($buyer['buyer_city']), //Địa chỉ người mua hàng
            'buyerCountry'  => urlencode($buyer['buyer_country']), //Địa chỉ người mua hàng
            'paymentHours'  => '24', //Địa chỉ người mua hàng
        );

        $post_url = 'https://alepay.vn/checkout/v1/request-order';
        $data = $this->encrypt(json_encode($params));

        $post_field = array(
            'token'     => $this->getMcToken(),
            'data'      => $data,
            'checksum'  => md5($data . $this->getMcChecksum())
        );

        $nl_result = $this->CheckoutCall($post_url, $post_field);
        CakeLog::info('input order:' . print_r($params, true), 'payment');
        CakeLog::info('create order ale:' . print_r( $nl_result, true), 'payment');
        if( isset($nl_result['errorCode']) && $nl_result['errorCode'] == '000'){
            return $this->decrypt($nl_result['data']);
        }
        return false;
    }

    public function getTransactionDetail($ale_token){
        $post_field = array('transactionCode' => $ale_token );
        $post_url = 'https://alepay.vn/checkout/v1/get-transaction-info';
        $nl_result = $this->CheckoutCall($post_url, $post_field);
        if( isset($nl_result['errorCode']) && $nl_result['errorCode'] == '000'){
            return $this->decrypt($nl_result['data']);
        }
        return false;
    }
}