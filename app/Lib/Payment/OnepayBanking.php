<?php

class OnepayBanking {
    
    private $access_key ;
	private $secret ;

    private $user_token;
    private $game_app;
    private $order_id;

    private $note = ' ';

    function __construct($access_key, $secret)
    {
        $this->access_key   = $access_key;
        $this->secret       = $secret;
    }

    public function getUserToken() {
        return $this->user_token;
    }

    public function setUserToken($user_token) {
        $this->user_token = $user_token;
    }

    public function getGameApp() {
        return $this->game_app;
    }

    public function setGameApp($game_app) {
        $this->game_app = $game_app;
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function setOrderId($order_id) {
        $this->order_id = $order_id;
    }

    public function getNote() {
        return $this->note;
    }

    public function setNote($note) {
        $this->note = $note;
    }

    public function execPostRequest($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    # amount: số tiền vnđ
    public function create($amount){
        $access_key = $this->access_key;
        $secret     = $this->secret;
        $return_url = urlencode(Configure::read('OnepayBanking.ReturnUrl') . '?app=' . $this->getGameApp() . '&qtoken=' . $this->getUserToken());
        $order_id   = $this->getOrderId();
        $order_info = $this->getNote();

        $data = "access_key=" . $access_key;
        $data .= "&amount=" . $amount;
        $data .= "&order_id=" . $order_id;
        $data .= "&order_info=" . $order_info;
        $signature = hash_hmac("sha256", $data, $secret);

        $data .= "&return_url=" . $return_url;
        $data.= "&signature=" . $signature;

        $pay_url = false;
        try {
            $url = 'https://api.pay.truemoney.com.vn/visa-charging/api/handle/request';
            $json_bankCharging = $this->execPostRequest($url, $data);
            $decode_bankCharging = json_decode($json_bankCharging, true);  // decode json
            if( !empty($decode_bankCharging["pay_url"]) ) $pay_url = $decode_bankCharging["pay_url"];
        }catch (Exception $e){
            CakeLog::error('error create onepay - ' . print_r($e, true), 'payment');
        }
        $pay_url .= '&lang=en';
        CakeLog::info('onepay url :' . print_r($pay_url,true), 'payment');
        CakeLog::info('onepay send data :' . print_r($data,true), 'payment');
        return $pay_url;
    }

    public function order($amount){
        $access_key = $this->access_key;
        $secret     = $this->secret;
        $return_url = Configure::read('OnepayBanking.ReturnUrl') . '?app=' . $this->getGameApp() . '&qtoken=' . $this->getUserToken();
        $order_id   = $this->getOrderId();
        $order_info = $this->getNote();
        $command = 'request_transaction';

        $data = "access_key=" . $access_key;
        $data .= "&amount=" . $amount;
        $data .= "&command=" . $command;
        $data .= "&order_id=" . $order_id;
        $data .= "&order_info=" . $order_info;

        $dataSign = $data . "&return_url=" . $return_url;
        $signature = hash_hmac("sha256", $dataSign, $secret);

        $data .= "&return_url=" . urlencode($return_url);
        $data.= "&signature=" . $signature;

        $pay_url = false;
        try {
            $url = 'http://api.1pay.vn/bank-charging/service/v2';
            $json_bankCharging = $this->execPostRequest($url, $data);
            $decode_bankCharging = json_decode($json_bankCharging, true);  // decode json
            if( !empty($decode_bankCharging["pay_url"]) ) $pay_url = $decode_bankCharging["pay_url"];
        }catch (Exception $e){
            CakeLog::error('error create onepay - ' . print_r($e, true), 'payment');
        }
        CakeLog::info('onepay url :' . print_r($pay_url,true), 'payment');
        CakeLog::info('onepay send data :' . print_r($data,true), 'payment');
        return $pay_url;
    }

    public function close($trans_ref){
        $access_key = $this->access_key;
        $secret     = $this->secret;
        $command    = 'close_transaction';

        $data = "access_key=" . $access_key;
        $data .= "&command=" . $command;
        $data .= "&trans_ref=" . $trans_ref;

        $signature = hash_hmac("sha256", $data, $secret);

        $data.= "&signature=" . $signature;

        try {
            $url = 'http://api.1pay.vn/bank-charging/service/v2';
            $json_bankCommit = $this->execPostRequest($url, $data);
            $result = json_decode($json_bankCommit, true);  // decode json
            CakeLog::info('onepay commit data :' . print_r($result, true) ,'payment');

            return $result;
        }catch (Exception $e){
            CakeLog::error('error create onepay - ' . print_r($e, true), 'payment');
        }

        return false;
    }
}