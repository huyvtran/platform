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
            $url = 'http://visa.1pay.vn/visa-charging/api/handle/request';
            $json_bankCharging = $this->execPostRequest($url, $data);
            $decode_bankCharging = json_decode($json_bankCharging, true);  // decode json
            if( !empty($decode_bankCharging["pay_url"]) ) $pay_url = $decode_bankCharging["pay_url"];
        }catch (Exception $e){
            CakeLog::error('error create onepay - ' . print_r($e, true), 'payment');
        }
        return $pay_url;
    }
}