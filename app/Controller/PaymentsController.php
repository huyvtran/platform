<?php

App::uses('AppController', 'Controller');
App::uses('PaymentLib', 'Payment');

class PaymentsController extends AppController {
	public function beforeFilter()
	{
		parent::beforeFilter();
        $this->Auth->allow(array(
            'api_pay', 'api_charge'
        ));
	}

    public $components = array(
        'Search.Prg'
    );

    public function api_pay(){
        $data = $this->pay(true);

        $result = array(
            'status' => 1,
            'message' => 'error'
        );
        if( !empty($data) ){
            $result = array(
                'status' => 0,
                'message' => 'success',
                'data' => $data
            );
        }

        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }

	public function pay($return = false)
	{
//		 echo 'Hệ thống thanh toán đang được bảo trì, và sẽ online trong thời gian sớm nhất. Chúng tôi xin lỗi vì sự bất tiện này.';
//		 die();

        $result_api = false;

        $this->layout = 'payment';

		# load for view
		$this->loadModel('Payment');
		
		$game = $this->Common->currentGame();
		if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            if($return) return false;
			throw new NotFoundException('Vui lòng login');
		}
		$user = $this->Auth->user();

		$paymentLib = new PaymentLib();
		# check to see if there is unresolved payment

        if ($this->request->is('post')) {
            $chanel = Payment::CHANEL_VIPPAY; // default
            $this->loadModel('Game');
            if( !empty($game['group']) && $game['group'] == Game::GROUP_R01 ) $chanel = Payment::CHANEL_VIPPAY;
            if( !empty($game['group']) && $game['group'] == Game::GROUP_R02 ) $chanel = Payment::CHANEL_VIPPAY_2;

            # chuyển kênh
            #$chanel = Payment::CHANEL_VIPPAY_2;

            if( in_array($game['app'], array(
                'd77a238697e63e5056810448d460c0d7', 'ced3d169ffdb099ee6fede9d8f923f60', //r13
                'a3fb6fd597a695212ec9cbd1f533f5e1', 'c8e35bf746e1f07c018719f605a1ae39', //r14
                '52fc9a9c80be1d0c339d420e98ab7120', '297b1557bfe2e3737731e49308f34858', //r15
                '237c67407f187e08af1c07d2c801e374', '7b02ce3348f590d1bc0b4fc7fb9dc1b5', //r16
                '1fd09cdea352f3032abab576d7ef0b12', 'ff93e1c4fbd927edd69bb141faca7433', //r18
                'd70086ba1b6198172e8b3be7de88d292', 'e7a537af8477e537702126e1b982145f'  //r19
            ))){
//                $chanel = Payment::CHANEL_HANOIPAY;
                $chanel = Payment::CHANEL_VIPPAY_3;
            }

            if( in_array($game['app'], array(
                '09a6e4b219d357facd5014e3585aa831', '4d84ffc6edda35edf6d01eb426af0144',  //r28
                '227276fe0f1234cc6c5e8074b16863c4', 'a787027507eb2342a951ca7b566be031',  //p30
            ))){
                $chanel = Payment::CHANEL_VIPPAY_3;
            }

            $order_id = microtime(true) * 10000;

            # sử dụng inpay
            $keyRedis = 'error-payment-inpay-' . Payment::CHANEL_INPAY;
            App::import('Lib', 'RedisCake');
            $Redis = new RedisCake('action_count');
            $Redis->key = $keyRedis;
            $count = $Redis->get($keyRedis);
            if ($count < 9) {
                $chanel = Payment::CHANEL_INPAY;
                $order_id = date('YmdHms') . rand(100, 999);
            }

            $data = $this->request->data;
            $data = array_merge($data, array(
                'user_id' => $user['id'],
                'game_id' => $game['id'],
                'chanel' => $chanel,
                'status' => WaitingPayment::STATUS_WAIT,
                'time' => time(),
                'order_id' => $order_id
            ));

            $this->loadModel('Payment');
            $this->loadModel('WaitingPayment');
            try {
                $unresolvedPayment = $this->WaitingPayment->save($data);

                $dataSource = $this->Payment->getDataSource();
                $dataSource->begin();

                $test_type = 0;
                if(!empty($game['data']['payment']['testallowed'])){
                    $testList = $game['data']['payment']['testallowed'];
                    if( in_array($user['email'], array_map('trim', explode("\n", $testList))) ){
                        $test_type = 1;
                    }
                }
                if($test_type){
                    $price_test = array(10000, 20000, 30000, 50000, 100000, 200000, 300000, 500000);
                    if( $data['card_serial'] == '123456789' && in_array($data['card_code'], $price_test) )
                    $result = array(
                        'status'    => 0,
                        'messsage'  => 'success',
                        'data'      => array(
                            'time'  => $data['time'],
                            'type'  => $data['type'],
                            'chanel'    => $data['chanel'],

                            'order_id'  => $data['order_id'],
                            'user_id'   => $data['user_id'],
                            'game_id'   => $data['game_id'],

                            'card_code' => $data['card_code'],
                            'price'     => $data['card_code'],
                            'card_serial'   => $data['card_serial']
                        )
                    );
                }else{
                    # gọi đến api cổng thanh toán và check thẻ
                    $result = $paymentLib->callPayApi($data);
                }

                if( isset($result['status']) && $result['status'] == 0 && $data['order_id'] == $result['data']['order_id']){
                    # trạng thái thành công, lưu dữ liệu payment
                    $data_payment = array(
                        'waiting_id'	=> $unresolvedPayment['WaitingPayment']['id'],

                        'time'  => $data['time'],
                        'type'  => $data['type'],
                        'test'	=> $test_type,
                        'chanel'    => $data['chanel'],

                        'order_id'  => $result['data']['order_id'],
                        'user_id' 	=> $user['id'],
                        'account_id'=> $this->Common->getAccount(),
                        'game_id' 	=> $game['id'],

                        'card_code' => $result['data']['card_code'],
                        'price'     => $result['data']['price'],
                        'card_serial'   => $result['data']['card_serial']
                    );
                    $paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
                    $paymentLib->add($data_payment);
                    $result_api = $data_payment;
                    if(!$return) $this->render('/Payments/result');
                }elseif (!empty($result['status']) && $result['status'] == 1){
                    # trạng thái lỗi, thẻ đã sử dụng, hoặc thẻ không đúng
                    CakeLog::info('trạng thái lỗi, thẻ đã sử dụng, hoặc thẻ không đúng', 'payment');
                    $paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                    if(!$return) $this->render('/Payments/error');
                }else{
                    # chờ hệ thống cổng thanh toán
                    CakeLog::info('chờ hệ thống cổng thanh toán', 'payment');
                    $paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);
                    if(!$return) $this->render('/Payments/order');
                }
                $dataSource->commit();
            } catch (Exception $e) {
                CakeLog::error($e->getMessage());
                $dataSource->rollback();
            }
        }

        if($return) return $result_api;
	}

	public function api_charge(){
	    $result = array(
	        'status'    => 1,
            'mesage'    => 'empty'
        );

        $app = 'app';
        $token  = 'token';

        if( $this->request->header($app) ){
            $appKey = $this->request->header($app);
        }

        if ( $this->request->query('app_key') ) {
            $appKey = $this->request->query('app_key');
        } elseif ( $this->request->query('appkey') ) {
            $appKey = $this->request->query('appkey');
        } elseif ( $this->request->query('app') ) {
            $appKey = $this->request->query('app');
        }

        if( $this->request->header($token) ){
            $accessToken = $this->request->header($token);
        }

        if ( $this->request->query('access_token') ) {
            $accessToken = $this->request->query('access_token');
        }elseif ( $this->request->query('token') ){
            $accessToken = $this->request->query('token');
        }

        if (!isset($appKey, $accessToken)) {
            $result = array(
                'status'    => 2,
                'mesage'    => 'empty token or appkey'
            );
            goto end;
        }

        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            $result = array(
                'status'    => 3,
                'mesage'    => 'Invalid token or appkey'
            );
            goto end;
        }
        $user = $this->Auth->user();

        $price = $sign_input = false;
        if( !empty($this->request->data('price')) ){
            $price = $this->request->data('price');
        }elseif ( !empty($this->request->query('price')) ){
            $price = $this->request->query('price');
        }

        if( !is_numeric($price) || $price <= 0 ){
            $result = array(
                'status'    => 7,
                'mesage'    => 'Invalid price'
            );
            goto end;
        }

        if( !empty($this->request->data('sign')) ){
            $sign_input = $this->request->data('sign');
        }elseif ( !empty($this->request->query('sign')) ){
            $sign_input = $this->request->query('sign');
        }

        if( empty($price) || empty($sign_input) ){
            $result = array(
                'status' => 4,
                'message' => 'Necessary data is missing'
            );
            goto end;
        }

        $paymentLib = new PaymentLib();
        # update payment user khi ingame trả về
        # dữ liệu truyền sang `price`, `sign`
        $data = array(
            'user_id'   => $user['id'],
            'game_id'   => $game['id'],
            'time'      => time(),
            'order_id'  => microtime(true) * 10000,
            'price'     => $price,
            'sign'      => $sign_input
        );

        $sign = md5( $game['app'] . $game['secret_key'] . $accessToken . $data['price'] );
        if( empty($data['sign']) || $sign != $data['sign'] ){
            CakeLog::error('sign api charge:'. $sign, 'payment');
            $result = array(
                'status'    => 5,
                'message'   => 'The sign is incorrect'
            );
            goto end;
        }

        if( $paymentLib->sub($data) ){
            $result = array(
                'status'    => 0,
                'mesage'    => 'success'
            );
            goto end;
        }else{
            $result = array(
                'status'    => 6,
                'mesage'    => 'error'
            );
            goto end;
        }

        end:
        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }

    public function admin_index(){
        $this->layout = 'default_bootstrap';

        $this->Prg->commonProcess();
        $this->request->data['Payment'] = $this->passedArgs;

        $parsedConditions = array();
        if(!empty($this->passedArgs)) {
            $parsedConditions = $this->Payment->parseCriteria($this->passedArgs);
        }

        if( !empty($this->passedArgs) && empty($parsedConditions)
        ){
            if (	(count($this->passedArgs) == 1 && empty($this->passedArgs['page']))
                ||	count($this->passedArgs) > 1
            ) {
                $this->Session->setFlash("Can not find anyone match this conditions", "error");
            }
        }

        $parsedConditions = array_merge(array(
            'Payment.game_id' => $this->Session->read('Auth.User.permission_game_default')
        ), $parsedConditions);

        $games = $this->Payment->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array(
                'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
            )
        ));

        $this->paginate = array(
            'Payment' => array(
                'fields' => array('Payment.*', 'User.username', 'User.id', 'Game.title', 'Game.os'),
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User'
                ),
                'order' => array('Payment.id' => 'DESC'),
                'recursive' => -1,
                'limit' => 20
            )
        );

        $payments = $this->paginate();

        $chanels = array(
            Payment::CHANEL_INPAY       => 'Inpay',
            Payment::CHANEL_VIPPAY      => 'Vippay',
            Payment::CHANEL_VIPPAY_2    => 'Vippay 2',
            Payment::CHANEL_HANOIPAY    => 'Hanoipay',
            Payment::CHANEL_PAYPAL      => 'Paypal',
            Payment::CHANEL_MOLIN       => 'Molin',
            Payment::CHANEL_ONEPAY      => '1Pay',
            Payment::CHANEL_ONEPAY_2    => '1Pay 2',
            Payment::CHANEL_PAYMENTWALL => 'PaymentWall',
            Payment::CHANEL_APPOTA      => 'Appota',
        );

        $types = array(
            Payment::TYPE_NETWORK_VIETTEL   => 'Viettel',
            Payment::TYPE_NETWORK_VINAPHONE => 'Vinaphone',
            Payment::TYPE_NETWORK_MOBIFONE  => 'Mobifone',
            Payment::TYPE_NETWORK_GATE      => 'Gate',
            Payment::TYPE_NETWORK_BANKING   => 'Visa'
        );

        $this->set(compact('payments', 'games', 'chanels', 'types'));
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

    public function admin_inpay(){
        $this->view = 'pay';
        $this->layout = 'payment';

        $this->loadModel('Payment');

        # sử dụng inpay
        if ($this->request->is('post')) {

            $keyRedis = 'error-payment-inpay-' . Payment::CHANEL_INPAY;
            App::import('Lib', 'RedisCake');
            $Redis = new RedisCake('action_count');
            $Redis->key = $keyRedis;
            $count = $Redis->get($keyRedis);
            if ($count < 9) {
                $chanel = Payment::CHANEL_INPAY;
                $order_id = date('YmdHms') . rand(100, 999);
            } else {
                echo 'quá 10 lần gạch thẻ lỗi, vui lòng chờ 5 phút';
                die;
            }

            $this->loadModel('WaitingPayment');

            $data = $this->request->data;
            $data = array_merge($data, array(
                'chanel' => $chanel,
                'order_id' => $order_id,
                'user_id' => 1,
                'game_id' => 1,
                'status' => WaitingPayment::STATUS_WAIT,
                'time' => time(),
            ));

            $unresolvedPayment = $this->WaitingPayment->save($data);

            App::import('Lib', 'Inpay'); #quanvuhong.riotgame@gmail.com
            $inppay = new Inpay('0455', 'XPCQW6L28SHN0HSV', '0VIPEZ3KOW4B5L88W1PW');
            $result = $inppay->call($data);

            $paymentLib = new PaymentLib();
            if($result['status'] == 0) {
                $this->Session->setFlash('Nạp thẻ thành công');
                $paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
            }else{
                if( !empty($result['data']['obj']['errorDesc']) ) {
                    $this->Session->setFlash($result['data']['obj']['errorDesc']);
                }else{
                    $this->Session->setFlash('Nạp thẻ thất bại');
                }

                $paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
            }
            $result = $result['data'];

            $this->set(compact('result'));
            $this->view = 'admin_inpay';
            $this->layout = 'default_bootstrap';
        }
    }
}
