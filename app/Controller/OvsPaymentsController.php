<?php

App::uses('AppController', 'Controller');
App::uses('PaymentLib', 'Payment');

class OvsPaymentsController extends AppController {
	public function beforeFilter()
	{
		parent::beforeFilter();
        $this->Auth->allow(array(
            'pay_error', 'pay_paymentwall_wait', 'pay_paymentwall_response',
            'pay_paymentwall_response_sms', 'pay_paymentwall_response_visa'
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
        $this->Common->setTheme();
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
        $this->set('title_for_app', 'Paypal');
        $this->Common->setTheme();

//        App::import('Lib', 'RedisCake');
//        $Redis = new RedisCake('action_count');
//        $redis_price = $Redis->get('paypal_key_' . Configure::read('Paypal.clientId'));
//        if( $redis_price > 490 ) $this->view = 'maintain';
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

        $payment_id = $this->request->query('paymentId');
        $payer_id  = $this->request->query('PayerID');
        if( empty($payment_id) || empty($payer_id) ){
            CakeLog::error(__('Lỗi giao dịch paypal') , 'payment');
            goto end;
        }

        # xử lý mua hàng qua paypal
        try {
            $token = $this->request->query('qtoken');
            App::uses('Paypal', 'Payment');
            $paypal = new Paypal($game['app'], $token);
            $paypalObj = $paypal->getPayment($payment_id);

            if (is_object($paypalObj) && $paypalObj->getState() == 'created') {
                $result = $paypal->execute($payment_id, $payer_id);
                if ($result->getState() == 'approved') {
                    $transactionObj = $result->getTransactions();
                    if (!empty($transactionObj[0]) && is_object($transactionObj[0])) {
                        $transactionObj = $transactionObj[0];
                        $orderId = $transactionObj->getInvoiceNumber();

                        $amongObj = $transactionObj->getAmount();

                        $relatedObj = $transactionObj->getRelatedResources();
                        $relatedObj = $relatedObj[0];

                        $saleObj = $relatedObj->getSale();

                        # ghi log paypal_order
                        $data_paypal = array(
                            'user_id' => $user['id'],
                            'game_id' => $game['id'],
                            'order_id' => $orderId,
                            'paypal_id' => $paypalObj->getId(),
                            'state' => $paypalObj->getState(),
                            'paypal_create_time' => $paypalObj->getCreateTime(),
                            'paypal_update_time' => $paypalObj->getUpdateTime(),
                            'amount_total' => $amongObj->getTotal(),
                            'amount_currency' => $amongObj->getCurrency(),
                            'sale_state' => $saleObj->getState(),
                            'sale_id' => $saleObj->getId(),
                        );

                        $this->loadModel('PaypalOrder');
                        $existedAOrder = $this->PaypalOrder->find('first', array(
                            'conditions' => array(
                                'PaypalOrder.order_id' => $orderId
                            )
                        ));

                        if (!empty($existedAOrder)) {
                            CakeLog::error(__('Giao dịch đã tồn tại'), 'payment');
                            goto end;
                        }

                        $paypalOrderObj = new PaypalOrder();
                        $paypalOrderObj->save($data_paypal);

                        $this->loadModel('WaitingPayment');
                        $this->WaitingPayment->recursive = -1;
                        $wating_payment = $this->WaitingPayment->findByOrderId($orderId);

                        # cộng xu
                        if (isset($wating_payment['WaitingPayment']['status'])
                            && $wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_QUEUEING
                            && $data_paypal['sale_state'] == 'completed'
                        ) {
                            $price = $wating_payment['WaitingPayment']['price'];
                            $price += 0.2*$price;
                            $price_end = $wating_payment['WaitingPayment']['price'] - (6801 + ($wating_payment['WaitingPayment']['price'])*0.045);
                            $data_payment = array(
                                'order_id'  => $orderId,
                                'user_id'   => $user['id'],
                                'game_id'   => $game['id'],
                                'price'     => $price,
                                'price_end'     => $price_end,
                                'time' => time(),
                                'type' => $wating_payment['WaitingPayment']['type'],
                                'chanel' => $wating_payment['WaitingPayment']['chanel'],
                                'waiting_id' => $wating_payment['WaitingPayment']['id']
                            );

                            $data_view = array(
                                'order_id'  => $orderId,
                                'price_end' => $price,
                                'price_game'=> 0,
                            );
                            $this->set('data_payment', $data_view);

                            $sdk_message = __("Giao dịch thành công.");
                            $status_sdk = 0;
                            $this->view = 'success';
                            $transaction_status = true;

                            # xử lý queue
                            App::import('Lib', 'RedisCake');
                            $Redis = new RedisCake('action_count');
                            $redis_price = $Redis->get('paypal_key_' . Configure::read('Paypal.clientId'));
                            if( empty($redis_price) ) $redis_price = $amongObj->getCurrency();
                            else $redis_price += $amongObj->getCurrency();
                            $Redis->set('paypal_key_' . Configure::read('Paypal.clientId'), $redis_price);
                        }

                        App::uses('PaymentLib', 'Payment');
                        $paymentLib = new PaymentLib();
                        if ($transaction_status) {
                            $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
                            $paymentLib->add($data_payment);

                            if( Configure::read('Bot.Telegram') ) {
                                $text_telegram = "Type: Paypal" . "\n\r"
                                    . "Order Id: " . $orderId . "\n\r"
                                    . "Price: " . number_format($wating_payment['WaitingPayment']['price'], 0, '.', ',') . ' vnđ' . "\n\r"
                                    . "User: " . $user['username'] . "\n\r"
                                    . "Game: " . $game['title_os'] . "\n\r";
                                $apiToken = "612122610:AAGf477qu8IX0erRw6Ci3D2qFenRGfoNTV8";
                                $chat_id  = '-290998992';
                                App::import('Lib', 'BotTelegram');
                                $bot = new BotTelegram($apiToken, $chat_id);
                                $bot->pushNotify($text_telegram);
                            }
                        } else {
                            $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                        }
                    }
                }
            }
        }catch (Exception $e){
            CakeLog::error(__('Lỗi giao dịch paypal') . ':' . $e->getMessage(), 'payment');
        }

        end:
        if( !empty($game['data']['payment']['url_sdk']) ){
            $this->redirect($game['data']['payment']['url_sdk'] . '?msg=' . $sdk_message . '&status=' . $status_sdk);
        }
    }

    public function pay_onepay_index(){
        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_PAYPAL, 'VND');
        $this->view = 'maintain';

        try {
            $user = $this->Auth->user();
            CakeLog::info('User Contry of ' . $user['id'] . ' - ' . $user['country_code']);
            if(!in_array($user['country_code'], array('Philippines', 'United States')) ) {
                $country = $this->Payment->User->getCountry();
                if($user['id'] == 19054){
                    CakeLog::info('check country p17 quanvh:' . $country);
                }
                if (!in_array($country, array('Philippines', 'United States'))) {
                    $this->view = 'pay_onepay_index';
                }
            }
        }catch (Exception $e){
            CakeLog::info('error country:' . $e->getMessage() , 'payment' );
        }
    }

    public function pay_onepay_atm(){
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
        if( !empty($this->request->query('visa')) ){
            $type = Payment::TYPE_NETWORK_VISA;
        }

        # set chanel defaul, có thể sẽ đc check theo chanel truemoney
        $access_key = "w1g998earl15prvzs2k4";
        $secret = "xr4m3lpwhj0egvlj965armf6od606cm3";

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

        if( !empty($this->request->query('visa')) ){
            $orderOnepay = $onepay->create($product['Product']['platform_price']);
        }else{
            $orderOnepay = $onepay->orderAtm($product['Product']['platform_price']);
        }

        if( empty($orderOnepay) ){
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }
        CakeLog::info('url request payment truemoney:' . $orderOnepay);

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
        if( !empty($this->request->query['order_id']) ){
            $orderId = $this->request->query['order_id'] ;

            $this->loadModel('WaitingPayment');
            $this->WaitingPayment->recursive = -1;
            $wating_payment = $this->WaitingPayment->findByOrderIdAndUserId($orderId, $user['id']);

            $this->loadModel('Payment');
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
            CakeLog::info('data url callback - onepay atm:' . print_r($this->request->query, true) , 'payment');
            $this->OnepayOrder->save($data_onepay_order);

            # check cổng trả về và commit giao dịch lên cổng
            if( isset($wating_payment['WaitingPayment']['status'])
                && $wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_QUEUEING
            ) {
                $access_key = "w1g998earl15prvzs2k4";
                $secret = "xr4m3lpwhj0egvlj965armf6od606cm3";
                App::uses('OnepayBanking', 'Payment');
                $onepay = new OnepayBanking($access_key, $secret);
                $closeOnepay = $onepay->closeAtm($this->request->query['trans_ref']);
                if( !empty($closeOnepay['response_code']) && $closeOnepay['response_code'] == '00'){
                    # cộng xu
                    $price = 1.5 *($wating_payment['WaitingPayment']['price']);
                    $price_end = 0.989*($wating_payment['WaitingPayment']['price']) - 1100;
                    $data_payment = array(
                        'order_id' => $orderId,
                        'user_id' => $user['id'],
                        'game_id' => $game['id'],
                        'price' => $price,
                        'price_end' => $price_end,
                        'time' => time(),
                        'type' => $wating_payment['WaitingPayment']['type'],
                        'chanel' => $wating_payment['WaitingPayment']['chanel'],
                        'waiting_id' => $wating_payment['WaitingPayment']['id']
                    );

                    $this->view = 'success';
                    $sdk_message = __("Giao dịch thành công.");
                    $status_sdk = 0;
                    $transaction_status = true;
                }
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
	    $this->Common->setTheme();
        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_PAYPAL, 'USD');
        $this->set('title_for_app', 'Banking (visa, master ...)');
    }

    public function pay_paymentwall_visa(){
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
        $paymentWall->setUserId($user['id']);

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
        if( !empty($this->request->query('visa'))){
            $access_key = "91cf18a7e7363951c06332f8d0f06b9c";
            $secret = "075e964d39c99e7f7761452637ebaaf7";
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

        App::uses('PaymentWall', 'Payment');
        $paymentWall = new PaymentWall($access_key, $secret, $token, $game['app']);
        $paymentWall->setOrderId($order_id);
        $paymentWall->setNote($product['Product']['title']);
        $paymentWall->setUserCreated($user['created']);
        $paymentWall->setUserId($user['id']);

        if( !empty($this->request->query('visa'))){
            $url = $paymentWall->visa($product['Product']);
        }else{
            $url = $paymentWall->create($product['Product']);
        }

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

    public function pay_paymentwall_response_visa(){
	    $this->pay_paymentwall_response(true);
    }

    public function pay_paymentwall_response($visa = false){
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
                if( $visa ){
                    $access_key = "91cf18a7e7363951c06332f8d0f06b9c";
                    $secret = "075e964d39c99e7f7761452637ebaaf7";
                }

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
						// if( isset($this->request->query['PAYMENT_SYSTEM']) && $this->request->query['PAYMENT_SYSTEM'] == 'Mobiamo' ){
						    // #$price = ($wating_payment['WaitingPayment']['price'])/2;
                            // $price = ($price)*0.85;
						// }else{
						   // #$price = ($price)*0.85;
						// }

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
                            #$price = ($price)/2;
                            #$price = ($price)*0.85;

                            $type = Payment::TYPE_NETWORK_SMS;
                            $this->WaitingPayment->id = $wating_payment['WaitingPayment']['id'];
                            $this->WaitingPayment->saveField('type', $type, array('callbacks' => false));
                        }else{
						   #$price = ($price)*0.85;
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

    public function pay_appota_index(){
        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_PAYPAL, 'VND');
    }

    public function pay_appota_order(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login - appota banking', 'payment');
            throw new NotFoundException('Vui lòng login');
        }

        $productId = $this->request->query('productId');
        if( empty($this->request->query('productId')) ){
            CakeLog::error('Chưa chọn gói xu - appota banking', 'payment');
            throw new NotFoundException('Chưa chọn gói xu');
        }

        $this->loadModel('Product');
        $this->Product->recursive = -1;
        $product = $this->Product->findById($productId);

        if( empty($product) ){
            CakeLog::error('Không có gói xu phù hợp - appota banking', 'payment');
            throw new NotFoundException('Không có gói xu phù hợp');
        }

        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');

        $user = $this->Auth->user();
        $order_id = microtime(true) * 10000;

        $chanel = Payment::CHANEL_APPOTA;
        $type = Payment::TYPE_NETWORK_BANKING;

        # set chanel defaul, có thể sẽ đc check theo chanel (AppotaPay 1, AppotaPay 2 ...)
        $api_key    = 'A180561-7XJCXZ-ECC265A7F4C3B6E2';
        $api_secret = 'pY4Mt9c2AJfu8ZG5';

//        $this->loadModel('Game');
//        if (!empty($game['group']) && $game['group'] == Game::GROUP_R01) {
//            $chanel = Payment::CHANEL_ONEPAY;
//            $access_key = "diggr0l4g6k792oj528a";
//            $secret = "mq1kbecvhya1jgnrrskqmzegh93ogomq";
//        } else if (!empty($game['group']) && $game['group'] == Game::GROUP_R02) {
//            $chanel = Payment::CHANEL_ONEPAY_2;
//            $access_key = "xr13xjpekax55j3jgsfs";
//            $secret = "rq10xl9fn20i2qlrqwc9gwdkmsd7cukx";
//        }

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
        App::uses('AppotaPay', 'Payment');
        $appota = new AppotaPay($api_key, $api_secret, $game['app'], $token);
        $appota->setOrderId($order_id);
//        $onepay->setNote($product['Product']['title']);

        $client_ip = $this->Common->publicClientIp();
        $orderAppota = $appota->getPaymentBankUrl($product['Product']['platform_price'], $client_ip);

        if( empty($orderAppota) ){
            throw new NotFoundException('Lỗi tạo giao dịch, vui lòng thử lại');
        }

        # chuyển trạng thái queue trong giao dịch
        App::uses('PaymentLib', 'Payment');
        $payLib = new PaymentLib();
        $payLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);
        $this->redirect($orderAppota);
    }

    public function pay_appota_response(){
        $this->layout = 'payment';
        $this->view = 'error';
        $sdk_message = __("Giao dịch thất bại.");
        $status_sdk = 1;

        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login - appota banking', 'payment');
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        $transaction_status = false;
        if( !empty($this->request->query['order_id']) ){
            $orderId = $this->request->query['order_id'] ;

            $this->loadModel('WaitingPayment');
            $this->WaitingPayment->recursive = -1;
            $wating_payment = $this->WaitingPayment->findByOrderIdAndUserId($orderId, $user['id']);

            if( empty($wating_payment['WaitingPayment']) ) goto a;

            $this->loadModel('Payment');
            $this->loadModel('AppotaOrder');
            $data_appota_order = array(
                'order_id'      => $orderId,
                'status'        => $this->request->query['status'],
                'sandbox'       => $this->request->query['sandbox'],
                'user_id'       => $user['id'],
                'game_id'       => $game['id'],
                'amount'        => $this->request->query['amount'],
                'trans_id'      => $this->request->query['transaction_id'],
                'type'          => $this->request->query['type'],
                'currency'      => $this->request->query['currency'],
                'country_code'  => $this->request->query['country_code'],
                'chanel'        => $wating_payment['WaitingPayment']['chanel']
            );

            CakeLog::info('data url callback - appota:' . print_r($this->request->query, true) , 'payment');
            $this->AppotaOrder->save($data_appota_order);

            # check cổng trả về và commit giao dịch lên cổng
            # xử lý mua hàng qua appota
            if( $this->request->query['status'] == 1 && isset($wating_payment['WaitingPayment']['status'])
                && $wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_QUEUEING
            ) {
                # set chanel defaul, có thể sẽ đc check theo chanel (AppotaPay 1, AppotaPay 2 ...)
                $api_key    = 'A180561-7XJCXZ-ECC265A7F4C3B6E2';
                $api_secret = 'pY4Mt9c2AJfu8ZG5';
                $token = $this->request->header('token');

                App::uses('AppotaPay', 'Payment');
                $appota = new AppotaPay($api_key, $api_secret, $game['app'], $token);
                $appota->setOrderId($orderId);

                $verify_transaction = $appota->verifyBankTransactionIpnHash($this->request->query);
                if( $verify_transaction ) {
                    $check_transaction = $appota->checkTransaction();
                    # cộng xu
                    if (!empty($check_transaction) && $check_transaction['error_code'] == 0) {
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
                    } elseif (in_array($check_transaction['error_code'], array(40, 41, 91))) {
                        #Hệ thống cổng bảo trì hoặc trạng thái chờ
                        goto a;
                    }
                }
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

    public function admin_detail(){
	    if(!empty($this->request->params['named'])){
	        $this->loadModel('Payment');
	        $data = $model = false;
	        switch ($this->request->params['named']['chanel']){
                case Payment::CHANEL_ONEPAY :
                case Payment::CHANEL_ONEPAY_2:
                    $model = 'OnepayOrder';
                    break;
                case Payment::CHANEL_NL_ALE :
                    $model = 'NlvisaOrder';
                    $this->view = 'admin_detail_ale';
                    break;
            }

            if( $model ) {
                $this->loadModel($model);
                $this->{$model}->bindModel(array(
                    'hasOne' => array(
                        'WaitingPayment' => array(
                            'foreignKey' => false,
                            'conditions' => array_merge(
                                array('WaitingPayment.order_id = ' . $model .'.order_id')
                            )
                        ),
                        'User' => array(
                            'foreignKey' => false,
                            'conditions' => array_merge(
                                array('User.id = ' . $model .'.user_id')
                            )
                        ),
                        'Game' => array(
                            'foreignKey' => false,
                            'conditions' => array_merge(
                                array('Game.id = ' . $model .'.game_id')
                            )
                        )
                    )
                ));

                $data = $this->{$model}->find('all', array(
                    'fields' => array( $model .'.*', 'WaitingPayment.*', 'Game.title', 'Game.os',
                        'User.username', 'User.id', 'User.country_code', 'User.created'),
                    'conditions' => array(
                        $model .'.order_id' => $this->request->params['named']['order_id'],
                    ),
                    'recursive' => -1,
                    'contain' => array('WaitingPayment', 'User', 'Game'),
                ));
                $data = $data[0];
            }

            $this->layout = 'default_bootstrap';
            $this->set(compact('data', 'model'));
        }
    }

    public function pay_ale_index(){
        $this->Common->currentGame();
        $this->Common->setTheme();
        $this->layout = 'payment';

        $this->loadModel('Payment');
        $this->pay_index(Payment::CHANEL_PAYPAL, 'USD');
        $this->set('title_for_app', 'Banking (visa, master)');
    }

    public function pay_ale_order(){
        $token = $this->request->header('token');
        $this->set(compact('token'));

        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        if ($this->request->is('post') || $this->request->is('put')) {
            $productId = $this->request->query('productId');
            if( empty($this->request->query('productId')) ){
                throw new NotFoundException(__('Chưa chọn gói xu'));
            }

            $this->loadModel('Product');
            $this->Product->recursive = -1;
            $product = $this->Product->findById($productId);

            if( empty($product) ){
                throw new NotFoundException(__('Không có gói xu phù hợp'));
            }

            if (empty($this->request->data['buyer_name']) || empty($this->request->data['buyer_email'])
                || empty($this->request->data['buyer_phone']) || empty($this->request->data['buyer_address'])
                || empty($this->request->data['buyer_city']) || empty($this->request->data['buyer_country'])
            ) {
                $messageFlash = __('Thiếu thông tin nạp coin');
                if (empty($this->request->data['buyer_name'])) $messageFlash = __('Vui lòng điền họ tên');
                if (empty($this->request->data['buyer_email'])) $messageFlash = __('Vui lòng điền thông tin email');
                if (empty($this->request->data['buyer_phone'])) $messageFlash = __('Vui lòng điền thông tin điện thoại');
                if (empty($this->request->data['buyer_address'])) $messageFlash = __('Vui lòng điền thông tin nơi ở');
                if (empty($this->request->data['buyer_city'])) $messageFlash = __('Vui lòng điền thông tin city');
                if (empty($this->request->data['buyer_country'])) $messageFlash = __('Vui lòng điền thông tin country');
                $this->Session->setFlash($messageFlash, null, array(), 'payment');
                goto end;
            }

            # verify email
            if( empty(filter_var($this->request->data['buyer_email'], FILTER_VALIDATE_EMAIL)) ){
                $this->Session->setFlash(__("Thông tin email không chính xác"), null, array(), 'payment');
                goto end;
            }

            $this->loadModel('Payment');
            $this->loadModel('WaitingPayment');

            $chanel = Payment::CHANEL_NL_ALE;
            $type = Payment::TYPE_NETWORK_VISA;

            $order_id = microtime(true) * 10000;
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

            $mc_token = 'tqtLWMqnKkqi3NRP32amXwSxJuFOCL';
            $mc_checksum = 'Sj21QrpiNpI6DrFutfRWUetCwCK4CU';
            $mc_encrypt = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCIZlME8jWIGDQRmLQxmw/8Gd8vgcoHLPNoaAnmq8WKvQb2Tk6uI0wyOqOI2IHNZm/k5Wz6NQvsiFgLWTXhtpyvaMfAFLQzc9cYWy6yBd+56QGYiYIMJdsR1wIkBZLQ5UPQleVXrnyhs1NPnZVJU0BsRurmQiHFSi1mHqtiZUQ1RQIDAQAB';
            App::uses('AlePay', 'Payment');
            $aleObj = new AlePay($mc_token, $mc_checksum, $mc_encrypt, $game['app'], $token);
            $aleObj->setOrderId($order_id);

            # tính theo usd
            $orderNL = $aleObj->visa($product['Product']['price'], $this->request->data);

            # chuyển trạng thái queue trong giao dịch
            App::uses('PaymentLib', 'Payment');
            $payLib = new PaymentLib();

            if( empty($orderNL['token']) || empty($orderNL['checkoutUrl'])){
                $payLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                throw new NotFoundException(__('Lỗi tạo giao dịch, vui lòng thử lại'));
            }

            $this->loadModel('NlvisaOrder');
            $this->NlvisaOrder->save(array(
                'NlvisaOrder' => array(
                    'order_id' => $order_id,
                    'game_id' => $game['id'],
                    'user_id' => $user['id'],
                    'nl_token' => $orderNL['token'],
                    'buyer_data' => json_encode($this->request->data)
                )
            ));

            $payLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);
            $this->redirect($orderNL['checkoutUrl']);
            end:
        }

        $this->Common->setTheme();
        $this->set('title_for_app', 'Banking (visa, master)');
        $this->layout = 'payment';
    }

    public function pay_ale_response(){
        $this->layout = 'payment';
        $this->view = 'error';

        CakeLog::info('check request ale response:' . print_r($this->request->query, true), 'payment');
        $game = $this->Common->currentGame();
        $this->Common->setTheme();
        if( empty($game) || !$this->Auth->loggedIn() ){
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        $token = $this->request->header('token');
        $this->set(compact('token'));

        $transaction_status = false;

        if( empty($this->request->query['data'])
            ||  empty($this->request->query['checksum'])
            ||  empty($this->request->query['order_id'])
        ){
            goto end;
        }

        # sử lý thêm data và checksum ale

        $order_id = $this->request->query['order_id'] ;

        $this->loadModel('NlvisaOrder');
        $this->loadModel('WaitingPayment');
        $this->WaitingPayment->bindModel(array(
            'hasOne' => array(
                'NlvisaOrder' => array(
                    'foreignKey' => false,
                    'conditions' => array_merge(
                        array('WaitingPayment.order_id = NlvisaOrder.order_id')
                    )
                )
            )
        ));

        $this->WaitingPayment->recursive = 0;
        $wating_payment = $this->WaitingPayment->findByOrderIdAndUserId($order_id, $user['id']);

        App::uses('PaymentLib', 'Payment');
        $paymentLib = new PaymentLib();

        # check cổng trả về và commit giao dịch lên cổng
        if( isset($wating_payment['WaitingPayment']['status'])
            && $wating_payment['WaitingPayment']['status'] != WaitingPayment::STATUS_COMPLETED
            && !empty($wating_payment['NlvisaOrder']['nl_token'])
        ) {
            $mc_token = 'tqtLWMqnKkqi3NRP32amXwSxJuFOCL';
            $mc_checksum = 'Sj21QrpiNpI6DrFutfRWUetCwCK4CU';
            $mc_encrypt = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCIZlME8jWIGDQRmLQxmw/8Gd8vgcoHLPNoaAnmq8WKvQb2Tk6uI0wyOqOI2IHNZm/k5Wz6NQvsiFgLWTXhtpyvaMfAFLQzc9cYWy6yBd+56QGYiYIMJdsR1wIkBZLQ5UPQleVXrnyhs1NPnZVJU0BsRurmQiHFSi1mHqtiZUQ1RQIDAQAB';
            App::uses('AlePay', 'Payment');
            $aleObj = new AlePay($mc_token, $mc_checksum, $mc_encrypt, $game['app'], $token);
            $ale_reponse = $aleObj->getTransactionDetail($wating_payment['NlvisaOrder']['nl_token']);

            CakeLog::info('check request ale response 2:' . print_r($ale_reponse, true), 'payment');

            if( !empty($ale_reponse) ){
                $data_ale = array(
                    'total_amount'      => $ale_reponse['amount'],
                    'currency'          => $ale_reponse['currency'],
                    'nl_status'         => $ale_reponse['status'],
                    'buyer_name'        => $ale_reponse['buyerName'],
                    'buyer_email'       => $ale_reponse['buyerEmail'],
                    'buyer_phone'       => $ale_reponse['buyerPhone'],
                    'card_number'       => $ale_reponse['cardNumber'],
                    'nl_method'         => $ale_reponse['method'],
                    'nl_data'           => json_encode($ale_reponse),
                );

                $this->NlvisaOrder->id = $wating_payment['NlvisaOrder']['id'];
                $this->NlvisaOrder->save($data_ale);

                # chờ cổng xác nhận
                if( $ale_reponse['status'] == '150' ){
                    $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_REVIEW);
                    $this->view = 'error';
                    return ;
                }

                $price = $wating_payment['WaitingPayment']['price'];
                # tăng 30% all game
                $price += 0.3*$price ;

                # cộng xu
                $data_payment = array(
                    'waiting_id'	=> $wating_payment['WaitingPayment']['id'],
                    'time'          => time(),
                    'chanel'        => $wating_payment['WaitingPayment']['chanel'],
                    'type'          => $wating_payment['WaitingPayment']['type'],

                    'order_id'      => $order_id,
                    'user_id'       => $user['id'],
                    'game_id'       => $game['id'],

                    'price'         => $price,
                    'price_end'     => ($wating_payment['WaitingPayment']['price'])*0.965 - 7700,
                );

                $data_view = array(
                    'order_id'  => $order_id,
                    'price_end' => $price,
                    'price_game'=> 0,
                );
                $this->set('data_payment', $data_view);

                $this->view = 'success';
                $transaction_status = true;

                $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
                $paymentLib->add($data_payment);
            }
        }elseif ( isset($wating_payment['WaitingPayment']['status'])
            && $wating_payment['WaitingPayment']['status'] == WaitingPayment::STATUS_COMPLETED
        ){
            $transaction_status = true;
            goto end;
        }

        end:
        if( !$transaction_status && !empty($wating_payment['WaitingPayment']['id']) ){
            $paymentLib->setResolvedPayment($wating_payment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
        }
    }
}
