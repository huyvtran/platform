<?php
class NL {
    protected $merchant_id = 51483;
    protected $merchant_password = '3b6fb680e6f3ea7ffb0f977fe488b59d';
    protected $version = '3.1';
    protected $receiver_email = 'quanvuhong.riotgame@gmail.com';
    protected $payment_type = 1;
    protected $cur_code = 'USD';

    protected $user_token;
    protected $appkey;

    protected $order_id;

    function __construct($merchant_id, $merchant_password, $appkey, $user_token)
    {
        $this->merchant_id = $merchant_id;
        $this->merchant_password = $merchant_password;
        $this->appkey = $appkey;
        $this->user_token = $user_token;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchant_id;
    }

    /**
     * @return mixed
     */
    public function getMerchantPassword()
    {
        return $this->merchant_password;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @param string $receiver_email
     */
    public function setReceiverEmail($receiver_email)
    {
        $this->receiver_email = $receiver_email;
    }

    /**
     * @return string
     */
    public function getReceiverEmail()
    {
        return $this->receiver_email;
    }

    /**
     * @param int $payment_type
     */
    public function setPaymentType($payment_type)
    {
        $this->payment_type = $payment_type;
    }

    /**
     * @return int
     */
    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * @return string
     */
    public function getCurCode()
    {
        return $this->cur_code;
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

    public function CheckoutCall($post_field){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.nganluong.vn/checkout.api.nganluong.post.php');
        curl_setopt($ch, CURLOPT_ENCODING , 'UTF-8');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        $nl_result = new stdClass();
        if ($result != '' && $status==200){
            $xml_result = str_replace('&','&amp;',(string)$result);
            $nl_result  = simplexml_load_string($xml_result);
            $nl_result->error_message = $this->GetErrorMessage($nl_result->error_code);
        }
        else $nl_result->error_message = $error;
        return $nl_result;
    }

    public function GetErrorMessage($error_code) {
        $arrCode = array(
            '00' => 'Thành công',
            '99' => 'Lỗi chưa xác minh',
            '06' => 'Mã merchant không tồn tại hoặc bị khóa',
            '02' => 'Địa chỉ IP truy cập bị từ chối',
            '03' => 'Mã checksum không chính xác, truy cập bị từ chối',
            '04' => 'Tên hàm API do merchant gọi tới không hợp lệ (không tồn tại)',
            '05' => 'Sai version của API',
            '07' => 'Sai mật khẩu của merchant',
            '08' => 'Địa chỉ email tài khoản nhận tiền không tồn tại',
            '09' => 'Tài khoản nhận tiền đang bị phong tỏa giao dịch',
            '10' => 'Mã đơn hàng không hợp lệ',
            '11' => 'Số tiền giao dịch lớn hơn hoặc nhỏ hơn quy định',
            '12' => 'Loại tiền tệ không hợp lệ',
            '29' => 'Token không tồn tại',
            '80' => 'Không thêm được đơn hàng',
            '81' => 'Đơn hàng chưa được thanh toán',
            '110' => 'Địa chỉ email tài khoản nhận tiền không phải email chính',
            '111' => 'Tài khoản nhận tiền đang bị khóa',
            '113' => 'Tài khoản nhận tiền chưa cấu hình là người bán nội dung số',
            '114' => 'Giao dịch đang thực hiện, chưa kết thúc',
            '115' => 'Giao dịch bị hủy',
            '118' => 'tax_amount không hợp lệ',
            '119' => 'discount_amount không hợp lệ',
            '120' => 'fee_shipping không hợp lệ',
            '121' => 'return_url không hợp lệ',
            '122' => 'cancel_url không hợp lệ',
            '123' => 'items không hợp lệ',
            '124' => 'transaction_info không hợp lệ',
            '125' => 'quantity không hợp lệ',
            '126' => 'order_description không hợp lệ',
            '127' => 'affiliate_code không hợp lệ',
            '128' => 'time_limit không hợp lệ',
            '129' => 'buyer_fullname không hợp lệ',
            '130' => 'buyer_email không hợp lệ',
            '131' => 'buyer_mobile không hợp lệ',
            '132' => 'buyer_address không hợp lệ',
            '133' => 'total_item không hợp lệ',
            '134' => 'payment_method, bank_code không hợp lệ',
            '135' => 'Lỗi kết nối tới hệ thống ngân hàng',
            '140' => 'Đơn hàng không hỗ trợ thanh toán trả góp',);

        return $arrCode[(string)$error_code];
    }

    function GetTransactionDetail($nl_token){
        $params = array(
            'merchant_id'       => $this->getMerchantId() ,
            'merchant_password' => MD5($this->getMerchantPassword()),
            'version'           => $this->getVersion(),
            'function'          => 'GetTransactionDetail',
            'token'             => $nl_token
        );

        $post_field = '';
        foreach ($params as $key => $value){
            if ($post_field != '') $post_field .= '&';
            $post_field .= $key."=".$value;
        }

        $nl_result = $this->CheckoutCall($post_field);
        return $nl_result;
    }

    # tạo giao dịch
    # trả về link redirect
    # với loại thanh toán visa debit có 3 bank_code: VISA, MASTER
    public function visa( $payment_method, $bank_code, $amount, $buyer = array()){
        $return_url = Configure::read('NL.ReturnUrl')
            . '?app=' . $this->getAppkey()
            . '&qtoken='. $this->getUserToken()
            . '&order_id=' . $this->getOrderId();
        $cancel_url = Configure::read('NL.CancelUrl')
            . '?app=' . $this->getAppkey()
            . '&qtoken='. $this->getUserToken()
            . '&order_id=' . $this->getOrderId();

        $params = array(
            'cur_code'				=>	$this->getCurCode(),
            'function'				=> 'SetExpressCheckout',
            'version'				=> $this->getVersion(),
            'merchant_id'			=> $this->getMerchantId(), //Mã merchant khai báo tại NganLuong.vn
            'receiver_email'		=> $this->getReceiverEmail(),
            'merchant_password'		=> MD5($this->getMerchantPassword()), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)
            'order_code'			=> $this->getOrderId(), //Mã hóa đơn do website bán hàng sinh ra
            'total_amount'			=> $amount, //Tổng số tiền của hóa đơn
            'payment_method'		=> $payment_method, //Phương thức thanh toán, nhận một trong các giá trị 'VISA','ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'
            'bank_code'				=> $bank_code, //Phương thức thanh toán, nhận một trong các giá trị 'VISA','ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'
            'payment_type'			=> $this->getPaymentType(), //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
            'order_description'		=> 'Package ' . $amount, //Mô tả đơn hàng
            'return_url'			=> $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
            'cancel_url'			=> $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
            'buyer_fullname'		=> $buyer['buyer_fullname'], //Tên người mua hàng
            'buyer_email'			=> $buyer['buyer_email'], //Địa chỉ Email người mua
            'buyer_mobile'			=> $buyer['buyer_mobile'], //Điện thoại người mua
            #'buyer_address'			=> $buyer['address'], //Địa chỉ người mua hàng
        );
        $post_field = '';
        foreach ($params as $key => $value){
            if ($post_field != '') $post_field .= '&';
            $post_field .= $key."=".$value;
        }

        $nl_result = $this->CheckoutCall($post_field);
        return $nl_result;
    }
}