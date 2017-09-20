<?php

App::uses('AppController', 'Controller');
App::uses('PaymentLib', 'Payment');

class OvsPaymentsController extends AppController {
	public function beforeFilter()
	{
		parent::beforeFilter();
        $this->Auth->allow(array(
            'pay_error', 'pay_paymentwall_wait', 'pay_paymentwall_response',
            'pay_paymentwall_response_sms'
        ));
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

    public function pay_error(){
        $this->layout = 'payment';
        $this->view = 'error';
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
        $this->set('title_for_app', 'Banking (visa, master)');
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
        $type = Payment::TYPE_NETWORK_BANKING;

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
        $sdk_message = __("Giao dịch thất bại.");
        $status_sdk = 1;
        $transaction_status = false;

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
            CakeLog::info('paypal response:' . print_r($result, true), 'payment');
            if( !empty($result->transactions[0]->invoice_number) ){
                $orderId = $result->transactions[0]->invoice_number ;
                $this->loadModel('WaitingPayment');
                $this->WaitingPayment->recursive = -1;
                $wating_payment = $this->WaitingPayment->findByOrderId($orderId);

                # ghi log paypal_order
                $data_paypal = array(
                    'user_id'   => $user['id'],
                    'game_id'   => $game['id'],
                    'order_id'  => $orderId,
                    'paypal_id' => $result->id,
                    'state'     => $result->state,
                    'paypal_create_time'    => $result->create_time,
                    'paypal_update_time'    => $result->update_time,
                    'amount_total'          => $result->transactions[0]->amount->total,
                    'amount_currency'       => $result->transactions[0]->amount->currency,
                    'sale_state'            => $result->transactions[0]->related_resources[0]->sale->state,
                    'sale_id'               => $result->transactions[0]->related_resources[0]->sale->id,
                    'data'                  => json_encode($result),
                );
                $this->loadModel('PaypalOrder');
                $paypalOrderObj = new PaypalOrder();
                $paypalOrderObj->save($data_paypal);

                # cộng xu
                if( isset($wating_payment['WaitingPayment']['status'])
                    && $wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_QUEUEING
                    && $data_paypal['amount_currency'] == 'USD' && $data_paypal['sale_state'] == 'completed'
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

                    $this->view = 'success';
                    $sdk_message = __("Giao dịch thành công.");
                    $status_sdk = 0;
                    $transaction_status = true;
                }

                App::uses('PaymentLib', 'Payment');
                $paymentLib = new PaymentLib();
                if( $transaction_status ){
                    $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
                    $paymentLib->add($data_payment);
                }else{
                    $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                }
            }
        }

        if( !empty($game['data']['payment']['url_sdk']) ){
            $this->redirect($game['data']['payment']['url_sdk'] . '?msg=' . $sdk_message . '&status=' . $status_sdk);
        }
    }

    public function pay_onepay_index(){
        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_PAYPAL, 'VND');
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
        $type = Payment::TYPE_NETWORK_BANKING;
        # set chanel defaul, có thể sẽ đc check theo chanel (1Pay 1, 1Pay 2 ...)
        $access_key = "diggr0l4g6k792oj528a";
        $secret = "mq1kbecvhya1jgnrrskqmzegh93ogomq";

        $this->loadModel('Game');
        if (!empty($game['group']) && $game['group'] == Game::GROUP_R01) {
            $chanel = Payment::CHANEL_ONEPAY;
            $access_key = "diggr0l4g6k792oj528a";
            $secret = "mq1kbecvhya1jgnrrskqmzegh93ogomq";
        } else if (!empty($game['group']) && $game['group'] == Game::GROUP_R02) {
            $chanel = Payment::CHANEL_ONEPAY_2;
            $access_key = "xr13xjpekax55j3jgsfs";
            $secret = "rq10xl9fn20i2qlrqwc9gwdkmsd7cukx";
        }

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
        $onepay = new OnepayBanking($access_key, $secret);
        $onepay->setGameApp($game['app']);
        $onepay->setUserToken($token);
        $onepay->setOrderId($order_id);
        $onepay->setNote($product['Product']['title']);

        $orderOnepay = $onepay->create($product['Product']['platform_price']);
        #$orderOnepay = $onepay->order($product['Product']['platform_price']);

        if( empty($orderOnepay) ){
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }

        # chuyển trạng thái queue trong giao dịch
        App::uses('PaymentLib', 'Payment');
        $payLib = new PaymentLib();
        $payLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);
        $this->redirect($orderOnepay);
    }

    public function pay_onepay_response(){
        $this->layout = 'payment';
        $this->view = 'error';
        $sdk_message = __("Giao dịch thất bại.");
        $status_sdk = 1;

        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login - onepay banking', 'payment');
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        $transaction_status = false;
        if( !empty($this->request->query['response_code']) && !empty($this->request->query['order_id']) ){
            $orderId = $this->request->query['order_id'] ;

            $this->loadModel('WaitingPayment');
            $this->WaitingPayment->recursive = -1;
            $wating_payment = $this->WaitingPayment->findByOrderIdAndUserId($orderId, $user['id']);

            $this->loadModel('Payment');
            # check cổng trả về và commit giao dịch lên cổng
            if( $this->request->query['response_code'] == '00' && isset($wating_payment['WaitingPayment']['status'])
                && $wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_QUEUEING
            ) {
                $this->loadModel('OnepayOrder');
                $data_onepay_order = array(
                    'order_id'      => $orderId,
                    'order_info'    => $this->request->query['order_info'],
                    'order_type'    => $this->request->query['order_type'],
                    'user_id'       => $user['id'],
                    'game_id'       => $game['id'],
                    'amount'        => $this->request->query['amount'],
                    'card_name'     => $this->request->query['card_name'],
                    'card_type'     => $this->request->query['card_type'],
                    'response_code' => $this->request->query['response_code'],
                    'trans_status'  => $this->request->query['trans_status'],
                    'trans_ref'     => $this->request->query['trans_ref'],
                    'chanel'        => $wating_payment['WaitingPayment']['chanel']
                );
                CakeLog::info('data url callback - onepay:' . print_r($this->request->query, true) , 'payment');
                $this->OnepayOrder->save($data_onepay_order);

                # cộng xu
                $data_payment = array(
                    'order_id' => $orderId,
                    'user_id' => $user['id'],
                    'game_id' => $game['id'],
                    'price' => $wating_payment['WaitingPayment']['price'],
                    'time' => time(),
                    'type' => $wating_payment['WaitingPayment']['type'],
                    'chanel' => $wating_payment['WaitingPayment']['chanel'],
                    'waiting_id' => $wating_payment['WaitingPayment']['id']
                );

                $this->view = 'success';
                $sdk_message = __("Giao dịch thành công.");
                $status_sdk = 0;
                $transaction_status = true;
            }elseif ( isset($wating_payment['WaitingPayment']['status'])
                && $wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_COMPLETED
            ){
                goto a;
            }

            App::uses('PaymentLib', 'Payment');
            $paymentLib = new PaymentLib();
            if( $transaction_status ){
                $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
                $paymentLib->add($data_payment);
            }else{
                $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
            }

            a:
            if( !empty($game['data']['payment']['url_sdk']) ){
                $this->redirect($game['data']['payment']['url_sdk'] . '?msg=' . $sdk_message . '&status=' . $status_sdk);
            }
        }
    }
    
    public function pay_paymentwall_index(){
        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_PAYPAL, 'USD');
        $this->set('title_for_app', 'Banking (visa, master ...)');
    }

    public function pay_paymentwall_bank(){
        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_PAYPAL, 'USD');
        $this->set('title_for_app', 'Banking (visa, master ...)');
    }

    public function pay_paymentwall_card(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login - paymentwall', 'payment');
            throw new NotFoundException('Vui lòng login');
        }

        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');

        $user = $this->Auth->user();
        $order_id = microtime(true) * 10000;

        $chanel = Payment::CHANEL_PAYMENTWALL;
        $type = Payment::TYPE_NETWORK_CARD;
        # set chanel defaul, có thể sẽ đc check theo chanel (Vippay, Vippay1, Vippay2...)
        $access_key = "66ea2fac02753c9d22ce29b6f9085927";
        $secret = "be6560c61bacc1ff6cb6dafbd3fc4d3e";
        $token = $this->request->header('token');

        # tạo giao dịch waiting_payment
        $data = array(
            'order_id'  => $order_id,
            'user_id'   => $user['id'],
            'game_id'   => $game['id'],
            'status'    => WaitingPayment::STATUS_WAIT,
            'time'      => time(),
            'type'      => $type,
            'chanel'    => $chanel,
        );
        $unresolvedPayment = $this->WaitingPayment->save($data);

        App::uses('PaymentWall', 'Payment');
        $paymentWall = new PaymentWall($access_key, $secret, $token, $game['app']);
        $paymentWall->setOrderId($order_id);
        $paymentWall->setUserCreated($user['created']);

        $url = $paymentWall->create_card();

        if( empty($url) ){
            CakeLog::error('Lỗi tạo giao dịch - paymentwall', 'payment');
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }

        CakeLog::info('url paymentwall:' . print_r($url,true), 'payment');

        # chuyển trạng thái queue trong giao dịch
        App::uses('PaymentLib', 'Payment');
        $payLib = new PaymentLib();
        $payLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);
        $this->redirect($url);
    }

    public function pay_paymentwall_order(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login - paymentwall', 'payment');
            throw new NotFoundException('Vui lòng login');
        }

        $productId = $this->request->query('productId');
        if( empty($this->request->query('productId')) ){
            CakeLog::error('Chưa chọn gói xu - paymentwall', 'payment');
            throw new NotFoundException('Chưa chọn gói xu');
        }

        $this->loadModel('Product');
        $this->Product->recursive = -1;
        $product = $this->Product->findById($productId);

        if( empty($product) ){
            CakeLog::error('Không có gói xu phù hợp - paymentwall', 'payment');
            throw new NotFoundException('Không có gói xu phù hợp');
        }

        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');

        $user = $this->Auth->user();
        $order_id = microtime(true) * 10000;

        $chanel = Payment::CHANEL_PAYMENTWALL;
        $type = Payment::TYPE_NETWORK_BANKING;
        # set chanel defaul, có thể sẽ đc check theo chanel (Vippay, Vippay1, Vippay2...)
        $access_key = "b16230d530d4e02c13801d17dfad7f84";
        $secret = "b1b48f5f2240ad9a5918e66c6feec5ff";
        $token = $this->request->header('token');

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

        App::uses('PaymentWall', 'Payment');
        $paymentWall = new PaymentWall($access_key, $secret, $token, $game['app']);
        $paymentWall->setOrderId($order_id);
        $paymentWall->setNote($product['Product']['title']);
        $paymentWall->setUserCreated($user['created']);

        $url = $paymentWall->create($product['Product']);

        if( empty($url) ){
            CakeLog::error('Lỗi tạo giao dịch - paymentwall', 'payment');
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }

        CakeLog::info('url paymentwall:' . print_r($url,true), 'payment');

        # chuyển trạng thái queue trong giao dịch
        App::uses('PaymentLib', 'Payment');
        $payLib = new PaymentLib();
        $payLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);
        $this->redirect($url);
    }

    public function pay_paymentwall_wait(){
        $this->layout = 'payment';
        $this->view = 'wait';
    }

    public function pay_paymentwall_response(){
        CakeLog::info('paymentwall pingback:' . print_r($this->request->query, true), 'payment');

        if ($this->request->query('app_key')) {
            $appKey = $this->request->query('app_key');
        } elseif ($this->request->query('appkey')) {
            $appKey = $this->request->query('appkey');
        } elseif ($this->request->query('app')) {
            $appKey = $this->request->query('app');
        }

        if ($this->request->query('access_token')) {
            $accessToken = $this->request->query('access_token');
        }elseif ($this->request->query('qtoken')){
            $accessToken = $this->request->query('qtoken');
        }


        if (!isset($appKey, $accessToken)) {
            throw new BadRequestException();
        }

        $this->loadModel('AccessToken');
        $this->loadModel('Game');

        $this->AccessToken->contain(array('User'));
        $user = $this->AccessToken->findByToken($accessToken);
        if (empty($user) || empty($user['User'])) {
            throw new BadRequestException('Invalid Token');
        }

        $this->Game->recursive = -1;
        $game = $this->Game->find('first', array(
            'conditions' => array('app' => $user['AccessToken']['app'])
        ));

        if (empty($game)) {
            throw new BadRequestException('Can not found this game');
        }

        $game = $game['Game'];
        $user = $user['User'];

        $result = 'ERROR';
        if(  !empty($this->request->query['order_id']) ){
            $orderId = $this->request->query['order_id'] ;

            $this->loadModel('WaitingPayment');
            $this->WaitingPayment->recursive = -1;
            $wating_payment = $this->WaitingPayment->findByOrderId($orderId);

            # check cổng trả về và commit giao dịch lên cổng
            App::uses('PaymentLib', 'Payment');
            $paymentLib = new PaymentLib();

            if( isset( $wating_payment['WaitingPayment']['status'] )
                && $wating_payment['WaitingPayment']['status'] != WaitingPayment::STATUS_COMPLETED
            ) {
                require_once ROOT. DS . 'vendors' . DS . 'PaymentWall' . DS . 'lib' . DS . 'paymentwall.php';

                $access_key = "b16230d530d4e02c13801d17dfad7f84";
                $secret = "b1b48f5f2240ad9a5918e66c6feec5ff";

                # cộng xu
                Paymentwall_Base::setApiType(Paymentwall_Base::API_GOODS);
                Paymentwall_Base::setAppKey($access_key);
                Paymentwall_Base::setSecretKey($secret);

                $pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
                if ($pingback->validate()) {
                    if ($pingback->isDeliverable()) {
                        $price_end = 0;
                        if( !empty($this->request->query['REVENUE']) ) {
                            $price_end = 22000 * $this->request->query['REVENUE'];
                        }

                        $test_type = 0;
                        if( isset($this->request->query['is_test']) && $this->request->query['is_test'] == 1 ) {
                            $test_type = 1;
                            $price_end = 22000;
                        }

                        $price = $wating_payment['WaitingPayment']['price'];
                        if( isset($this->request->query['PAYMENT_SYSTEM']) && $this->request->query['PAYMENT_SYSTEM'] == 'Mobiamo' ){
                            $price = ($wating_payment['WaitingPayment']['price'])/2;
                        }else{
                            $price = ($price)*0.85;
                        }

                        // deliver the product
                        $data_payment = array(
                            'order_id' => $orderId,
                            'user_id' => $user['id'],
                            'game_id' => $game['id'],
                            'price' => $price,
                            'time' => time(),
                            'type' => $wating_payment['WaitingPayment']['type'],
                            'chanel' => $wating_payment['WaitingPayment']['chanel'],
                            'waiting_id' => $wating_payment['WaitingPayment']['id'],
                            'test'	=> $test_type,
                            'price_end' => $price_end
                        );

                        $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
                        $paymentLib->add($data_payment);
                    } else if ($pingback->isCancelable()) {
                        // withdraw the product
                        $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                    }
                    $result = 'OK';
                }else{
                    $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                    $result = $pingback->getErrorSummary();
                }
            }else{
                #log order_id empty
                if($wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_COMPLETED){
                    $result = 'OK';
                }
                CakeLog::info('paymentwall log order_id empty:' . print_r($this->request->query, true), 'payment');
            }
        }
        echo $result; die;
    }

    public function pay_paymentwall_response_sms(){
        CakeLog::info('paymentwall pingback sms:' . print_r($this->request->query, true), 'payment');

        if ($this->request->query('app_key')) {
            $appKey = $this->request->query('app_key');
        } elseif ($this->request->query('appkey')) {
            $appKey = $this->request->query('appkey');
        } elseif ($this->request->query('app')) {
            $appKey = $this->request->query('app');
        }

        if ($this->request->query('access_token')) {
            $accessToken = $this->request->query('access_token');
        }elseif ($this->request->query('qtoken')){
            $accessToken = $this->request->query('qtoken');
        }


        if (!isset($appKey, $accessToken)) {
            throw new BadRequestException();
        }

        $this->loadModel('AccessToken');
        $this->loadModel('Game');

        $this->AccessToken->contain(array('User'));
        $user = $this->AccessToken->findByToken($accessToken);
        if (empty($user) || empty($user['User'])) {
            throw new BadRequestException('Invalid Token');
        }

        $this->Game->recursive = -1;
        $game = $this->Game->find('first', array(
            'conditions' => array('app' => $user['AccessToken']['app'])
        ));

        if (empty($game)) {
            throw new BadRequestException('Can not found this game');
        }

        $game = $game['Game'];
        $user = $user['User'];

        $result = 'ERROR';
        if(  !empty($this->request->query['order_id']) ){
            $orderId = $this->request->query['order_id'] ;

            $this->loadModel('WaitingPayment');
            $this->WaitingPayment->recursive = -1;
            $wating_payment = $this->WaitingPayment->findByOrderId($orderId);

            # check cổng trả về và commit giao dịch lên cổng
            App::uses('PaymentLib', 'Payment');
            $paymentLib = new PaymentLib();

            if( isset( $wating_payment['WaitingPayment']['status'] )
                && $wating_payment['WaitingPayment']['status'] != WaitingPayment::STATUS_COMPLETED
            ) {
                require_once ROOT. DS . 'vendors' . DS . 'PaymentWall' . DS . 'lib' . DS . 'paymentwall.php';

                $access_key = "66ea2fac02753c9d22ce29b6f9085927";
                $secret = "be6560c61bacc1ff6cb6dafbd3fc4d3e";

                # cộng xu
                Paymentwall_Base::setApiType(Paymentwall_Base::API_VC);
                Paymentwall_Base::setAppKey($access_key);
                Paymentwall_Base::setSecretKey($secret);

                $pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
                if ($pingback->validate()) {
                    $price = $pingback->getVirtualCurrencyAmount();
                    if ($pingback->isDeliverable()) {
                        $price_end = 0;
                        if( !empty($this->request->query['REVENUE']) ) {
                            $price_end = 22000 * $this->request->query['REVENUE'];
                        }

                        $test_type = 0;
                        if( isset($this->request->query['is_test']) && $this->request->query['is_test'] == 1 ) {
                            $test_type = 1;
                            $price_end = 22000;
                        }

                        $type = $wating_payment['WaitingPayment']['type'];
                        if( isset($this->request->query['PAYMENT_SYSTEM']) && $this->request->query['PAYMENT_SYSTEM'] == 'Mobiamo' ){
                            $price = ($price)/2;

                            $type = Payment::TYPE_NETWORK_SMS;
                            $this->WaitingPayment->id = $wating_payment['WaitingPayment']['id'];
                            $this->WaitingPayment->saveField('type', $type, array('callbacks' => false));
                        }


                        // deliver the product
                        $data_payment = array(
                            'order_id' => $orderId,
                            'user_id' => $user['id'],
                            'game_id' => $game['id'],
                            'price' => $price,
                            'time' => time(),
                            'type' => $type,
                            'chanel' => $wating_payment['WaitingPayment']['chanel'],
                            'waiting_id' => $wating_payment['WaitingPayment']['id'],
                            'test'	=> $test_type,
                            'price_end' => $price_end
                        );

                        $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
                        $paymentLib->add($data_payment);
                    } else if ($pingback->isCancelable()) {
                        // withdraw the product
                        $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                    }
                    $result = 'OK';
                }else{
                    $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                    $result = $pingback->getErrorSummary();
                }
            }else{
                #log order_id empty
                if($wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_COMPLETED){
                    $result = 'OK';
                }
                CakeLog::info('paymentwall log order_id empty:' . print_r($this->request->query, true), 'payment');
            }
        }
        echo $result; die;
    }
}
