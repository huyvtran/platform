<?php

class VippayBanking {
    
    private $merchant_id ;
	private $api_user ;
	private $api_password ;
    private $chanel ;

    private $user_token;
    private $game_app;
    private $order_id;

    private $note = '';

    function __construct($merchant_id, $api_user, $api_password, $chanel)
    {
        $this->merchant_id  = $merchant_id;
        $this->api_user     = $api_user;
        $this->api_password = $api_password;
        $this->chanel       = $chanel;
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

    # amount: số tiền
    # bank_type: mã visa (visa, master)
    public function create($amount, $bank_type){

    }
}