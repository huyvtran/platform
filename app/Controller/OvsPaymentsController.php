<?php

App::uses('AppController', 'Controller');
App::uses('PaymentLib', 'Payment');

class OvsPaymentsController extends AppController {
	public function beforeFilter()
	{
		parent::beforeFilter();
	}

    public function pay_list(){
        $this->layout = 'payment';

        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            throw new NotFoundException('Vui lòng login');
        }

        $token = $this->request->header('token');
        $this->set(compact('token', 'game'));
    }

    public function pay_index($chanel = array(), $currency = false){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            throw new NotFoundException('Vui lòng login');
        }
        $token = $this->request->header('token');

        if (!$currency) {
            $currency = 'USD';
        }

        //get list price
        $this->loadModel('Product');
        $products = $this->Product->find('all', array(
            'conditions' => array(
                'Product.game_id'   => $game['id'],
                'Product.chanel'    => $chanel,
            ),
            'order'     => array('Product.platform_price' => 'asc' ),
            'recursive' => -1
        ));

        $this->set(compact('products', 'currency', 'game', 'token'));
        $this->layout = 'payment';
        $this->loadModel('Payment');
    }

    public function pay_paypal_index(){
        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_PAYPAL, 'USD');
    }

    public function pay_paypal_order(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            throw new NotFoundException('Vui lòng login');
        }

        $token = $this->request->header('token');

        //get currency
        $currency = $this->request->query('currency');
        if (!$currency) {
            $currency = 'USD';
        } else {
            $currency = strtolower($currency);
        }

        $productId = $this->request->query('productId');
        if( empty($this->request->query('productId')) ){
            CakeLog::error('Chưa chọn gói xu - paypal', 'payment');
            throw new NotFoundException('Chưa chọn gói xu');
        }

        $this->loadModel('Product');
        $this->Product->recursive = -1;
        $product = $this->Product->findById($productId);

        if( empty($product) ){
            CakeLog::error('Không có gói xu phù hợp - paypal', 'payment');
            throw new NotFoundException('Không có gói xu phù hợp');
        }

        # xử lý mua hàng qua paypal
        App::uses('Paypal', 'Payment');
        $paypal = new Paypal($game['app'], $token);
        $linkPaypal = $paypal->buy($product['Product']['title'], $product['Product']['price'], $currency);
        if( empty($linkPaypal) ){
            CakeLog::error('Lỗi tạo giao dịch - paypal', 'payment');
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }
        $this->redirect($linkPaypal);
    }

    public function pay_paypal_response(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        $paypal_id = $this->request->query('paymentId');
        if( empty($paypal_id) ){
            CakeLog::error('Lỗi giao dịch - paypal response', 'payment');
            throw new NotFoundException('Lỗi giao dịch');
        }

        $clientId = Configure::read('Paypal.clientId');
        $secret = Configure::read('Paypal.secret');

        $paypal_token_url = Configure::read('Paypal.TokenUrl');
        $paypal_payment_url = Configure::read('Paypal.PaymentUrl');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paypal_token_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

        $result = curl_exec($ch);
        curl_close($ch);

        if(!empty($result)) {
            $json = json_decode($result);
            $accessToken = $json->access_token;

            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $paypal_payment_url . $paypal_id);
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $accessToken,
                'Accept: application/json',
                'Content-Type: application/json'
            ));

            $result = curl_exec($ch1);
            curl_close($ch1);

            echo "<pre>"; print_r(json_decode($result));die;
        }
    }

    public function pay_vippay_index(){
        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_VIPPAY, 'VND');
    }

    public function pay_vippay_order(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login - vippay banking', 'payment');
            throw new NotFoundException('Vui lòng login');
        }

        $token = $this->request->header('token');

        $productId = $this->request->query('productId');
        if( empty($this->request->query('productId')) ){
            CakeLog::error('Chưa chọn gói xu - vippay banking', 'payment');
            throw new NotFoundException('Chưa chọn gói xu');
        }

        $this->loadModel('Product');
        $this->Product->recursive = -1;
        $product = $this->Product->findById($productId);

        if( empty($product) ){
            CakeLog::error('Không có gói xu phù hợp - vippay banking', 'payment');
            throw new NotFoundException('Không có gói xu phù hợp');
        }
        debug($product);die;

        # xử lý mua hàng qua vippay
        App::uses('Paypal', 'Payment');
        $paypal = new Paypal($game['app'], $token);
        $linkPaypal = $paypal->buy($product['Product']['title'], $product['Product']['price'], $currency);
        if( empty($linkPaypal) ){
            CakeLog::error('Lỗi tạo giao dịch - vippay banking', 'payment');
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }
        $this->redirect($linkPaypal);
    }
}
