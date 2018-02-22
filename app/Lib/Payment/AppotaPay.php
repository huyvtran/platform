<?php
/**
 * Created by PhpStorm.
 * User: QUANVH
 * Date: 2/21/2018
 * Time: 2:47 PM
 */

class AppotaPay
{
    private $api_url    = 'https://api.appotapay.com/';
    private $api_key    = 'A180561-7XJCXZ-ECC265A7F4C3B6E2';
    private $api_secret = 'pY4Mt9c2AJfu8ZG5';
    private $lang       = 'en';
    private $version    = 'v1';
    private $method     = 'POST';

    private $game_app;
    private $user_token;
    private $order_id;

    public function __construct($api_key, $api_secret, $game_app, $user_token)
    {
        $this->api_key      = $api_key;
        $this->api_secret   = $api_secret;
        $this->game_app     = $game_app;
        $this->user_token   = $user_token;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @return string
     */
    public function getApiSecret()
    {
        return $this->api_secret;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->api_url;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
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
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
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
    public function getGameApp()
    {
        return $this->game_app;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param mixed $order_id
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    /*
     * function make request
     * url : string | url request
     * params : array | params request
     * method : string(POST,GET) | method request
     */
    private function makeRequest($url, $params)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // connect time out 5s
        #curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); // Time out 60s

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_error($ch)) {
            return false;
        }

        if ($status != 200) {
            curl_close($ch);
            return false;
        }
        // close curl
        curl_close($ch);

        return $result;
    }

    /*
    * function get payment bank url
    */
    public function getPaymentBankUrl( $amount, $client_ip, $state = '', $target = '')
    {
        // build api url
        $api_url = $this->getApiUrl() . $this->getVersion() . DS . 'services' . DS . 'pay_visa?api_key=' . $this->getApiKey() . '&lang=' . $this->getLang();
        // sandbox
        //$api_url = $this->getApiUrl() . $this->getVersion() . DS . 'sandbox' . DS . 'services' . DS . 'pay_visa?api_key=' . $this->getApiKey() . '&lang=' . $this->getLang();
        CakeLog::info('url request payment appota:' . $api_url);

        $success_url = $error_url = Configure::read('AppotaPay.ReturnUrl')
            . '?app=' . $this->getGameApp()
            . '&qtoken='. $this->getUserToken()
            . '&order_id=' . $this->getOrderId();
        $bank_id        = 0;

        // build params
        $params = array(
            'developer_trans_id'    => $this->getOrderId(), // Require param
            'amount'                => $amount, // Require param
            'client_ip'             => $client_ip, // Require param
            'state'                 => $state, // Optional param
            'target'                => $target, // Optional param
            'success_url'           => $success_url, // Optional param
            'error_url'             => $error_url, // Optional param
            'bank_id'               => $bank_id // Optional param
        );

        // request get payment url
        $result = $this->makeRequest($api_url, $params);

        // decode result
        $result = json_decode($result);

        // check result
        if (isset($result->error_code) && $result->error_code === 0) { // charging success
            $bank_options = $result->data->bank_options;
            return $bank_options[0]->url;
        }

        return false;
    }

    /*
    * function verify hash IPN for bank transaction
    * @param: pass your var $_POST that your server received from AppotaPay's server
    */
    public function verifyBankTransactionIpnHash($params)
    {
        // get params
        $status = $params['status'];
        $amount = $params['amount'];
        $type = $params['type'];
        $country_code = $params['country_code'];
        $currency = $params['currency'];
        $sandbox = $params['sandbox'];
        $state = $params['state'];
        $target = $params['target'];
        $transaction_id = $params['transaction_id'];
        $developer_trans_id = $params['developer_trans_id'];
        $transaction_type = $params['transaction_type'];
        $hash = $params['hash'];

        // check hash
        $check_hash = md5($amount . $country_code . $currency . $developer_trans_id . $sandbox . $state . $status . $target . $transaction_id . $transaction_type . $type . $this->getApiSecret());
        if ($check_hash !== $hash) {
            // return check hash fail
            return false;
        }

        // check transaction status
        // return transaction success
        if ($status == 1) {
            return true;
        }

        return false;
    }

    /*
    * function check transantion status
    * @param: developer_trans_id
    */
    public function checkTransaction()
    {
        // build api url
        $api_url = $this->getApiUrl() . $this->getVersion() . DS . 'services' . DS . 'check_transaction_status?api_key=' . $this->getApiKey() . '&lang=' . $this->getLang();
        // sandbox
        //$api_url = $this->getApiUrl() . $this->getVersion() . DS . 'sandbox' . DS . 'services' . DS . 'check_transaction_status?api_key=' . $this->getApiKey() . '&lang=' . $this->getLang();

        // build params
        $params = array(
            'developer_trans_id' => $this->getOrderId(),
            'transaction_type' => 'BANK'
        );

        // request check transaction
        $result = $this->makeRequest($api_url, $params);
        // decode result
        $result = json_decode($result);

        CakeLog::info('result appota:' . print_r($result, true), 'payment');

        // check result
        if (isset($result->error_code) && $result->error_code === 0) { // transaction success
            return array(
                'error_code'=> $result->error_code,
                'tran_id'   => $result->data->transaction_id,
                'amount'    => $result->data->amount
            );
        }

        return false;
    }
}