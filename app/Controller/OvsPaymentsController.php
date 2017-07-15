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

        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');

        $user = $this->Auth->user();
        $order_id = microtime(true) * 10000;

        $chanel = Payment::CHANEL_PAYPAL;
        $type = Payment::TYPE_NETWORK_PAYPAL;

        # tạo giao dịch waiting_payment
        $data = array(
            'order_id'  => $order_id,
            'user_id'   => $user['id'],
            'game_id'   => $game['id'],
            'price'     => $product['Product']['platform_price'],
            'status'    => WaitingPayment::STATUS_WAIT,
            'time'      => time(),
            'type'      => $type,
            'chanel'    => $chanel,
        );

        $unresolvedPayment = $this->WaitingPayment->save($data);

        # xử lý mua hàng qua paypal
        $token = $this->request->header('token');
        App::uses('Paypal', 'Payment');
        $paypal = new Paypal($game['app'], $token);
        $paypal->setOrderId($order_id);

        $linkPaypal = $paypal->buy($product['Product']['title'], $product['Product']['price'], $currency);
        if( empty($linkPaypal) ){
            CakeLog::error('Lỗi tạo giao dịch - paypal', 'payment');
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }

        # chuyển trạng thái queue trong giao dịch
        App::uses('PaymentLib', 'Payment');
        $payLib = new PaymentLib();
        $payLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);

        $this->redirect($linkPaypal);
    }

    public function pay_paypal_response(){
        $this->layout = 'payment';
        $this->view = 'error';
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
            $result = json_decode($result);
            if( !empty($result->transactions[0]->invoice_number) ){
                $orderId = $result->transactions[0]->invoice_number ;
                $this->loadModel('WaitingPayment');
                $this->WaitingPayment->recursive = -1;
                $wating_payment = $this->WaitingPayment->findByOrderId($orderId);

                # cộng xu
                if( isset($wating_payment['WaitingPayment']['status'])
                    && $wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_QUEUEING
                ){
                    $data_payment = array(
                        'order_id'  => $orderId,
                        'user_id'   => $user['id'],
                        'game_id'   => $game['id'],
                        'price'     => $wating_payment['WaitingPayment']['price'],
                        'time'      => time(),
                        'type'      => $wating_payment['WaitingPayment']['type'],
                        'chanel'    => $wating_payment['WaitingPayment']['chanel'],
                        'waiting_id'=> $wating_payment['WaitingPayment']['id']
                    );

                    App::uses('PaymentLib', 'Payment');
                    $paymentLib = new PaymentLib();
                    $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
                    $paymentLib->add($data_payment);

                    $this->view = 'success';
                }
            }
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

        $bank_type = $this->request->query('bank_type');
        if(!in_array($bank_type, array('Visa', 'Master'))){
            CakeLog::error('Loại thẻ không phù hợp - vippay banking', 'payment');
            throw new NotFoundException('Loại thẻ không phù hợp');
        }

        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');

        $user = $this->Auth->user();
        $order_id = microtime(true) * 10000;

        $chanel = Payment::CHANEL_VIPPAY;
        # set chanel defaul, có thể sẽ đc check theo chanel (Vippay, Vippay1, Vippay2...)
        $merchant_id = 8945;
        $api_user = "6433e60201c2412ca9d211ed2d9a8caa";
        $api_password = "f3197fbb40b748e9b6123cf2739bbdf2";
        $type = 'Vippay';

        # tạo giao dịch waiting_payment
        $data = array(
            'order_id'  => $order_id,
            'user_id'   => $user['id'],
            'game_id'   => $game['id'],
            'price'     => $product['Product']['platform_price'],
            'status'    => WaitingPayment::STATUS_WAIT,
            'time'      => time(),
            'type'      => $type,
            'chanel'    => $chanel,
        );

        $unresolvedPayment = $this->WaitingPayment->save($data);

        # xử lý mua hàng qua vippay
        $token = $this->request->header('token');
        App::uses('VippayBanking', 'Payment');
        $vippay = new VippayBanking($merchant_id, $api_user, $api_password);
        $vippay->setGameApp($game['app']);
        $vippay->setUserToken($token);
        $vippay->setOrderId($order_id);

        $orderVippay = $vippay->create($product['Product']['price'], $bank_type);
        if( empty($orderVippay) ){
            CakeLog::error('Lỗi tạo giao dịch - vippay banking', 'payment');
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }
        $this->redirect($orderVippay);
    }

    public function pay_onepay_index(){
        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_ONEPAY, 'VND');
    }

    public function pay_onepay_order(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login - onepay banking', 'payment');
            throw new NotFoundException('Vui lòng login');
        }

        $productId = $this->request->query('productId');
        if( empty($this->request->query('productId')) ){
            CakeLog::error('Chưa chọn gói xu - onepay banking', 'payment');
            throw new NotFoundException('Chưa chọn gói xu');
        }

        $this->loadModel('Product');
        $this->Product->recursive = -1;
        $product = $this->Product->findById($productId);

        if( empty($product) ){
            CakeLog::error('Không có gói xu phù hợp - onepay banking', 'payment');
            throw new NotFoundException('Không có gói xu phù hợp');
        }

        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');

        $user = $this->Auth->user();
        $order_id = microtime(true) * 10000;

        $chanel = Payment::CHANEL_ONEPAY;
        $type = Payment::TYPE_NETWORK_ONEPAY;
        # set chanel defaul, có thể sẽ đc check theo chanel (Vippay, Vippay1, Vippay2...)
        $access_key = "diggr0l4g6k792oj528a";
        $secret = "mq1kbecvhya1jgnrrskqmzegh93ogomq";

        # tạo giao dịch waiting_payment
        $data = array(
            'order_id'  => $order_id,
            'user_id'   => $user['id'],
            'game_id'   => $game['id'],
            'price'     => $product['Product']['platform_price'],
            'status'    => WaitingPayment::STATUS_WAIT,
            'time'      => time(),
            'type'      => $type,
            'chanel'    => $chanel,
        );

        $unresolvedPayment = $this->WaitingPayment->save($data);

        # xử lý mua hàng qua vippay
        $token = $this->request->header('token');
        App::uses('OnepayBanking', 'Payment');
        $vippay = new OnepayBanking($access_key, $secret);
        $vippay->setGameApp($game['app']);
        $vippay->setUserToken($token);
        $vippay->setOrderId($order_id);

        $orderOnepay = $vippay->create($product['Product']['platform_price']);

        if( empty($orderOnepay) ){
            CakeLog::error('Lỗi tạo giao dịch - vippay banking', 'payment');
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }
        $this->redirect($orderOnepay);
    }

    public function pay_onepay_response(){
        debug($this->request);die;
    }
}
