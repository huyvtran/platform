<?php

App::uses('AppController', 'Controller');

class ManualPaymentsController extends AppController {
    public $components = array(
        'Search.Prg'
    );

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	public function index(){
        # load for view
        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');
        $this->loadModel('CardManual');

        $this->layout = 'payment';

        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        if ($this->request->is('post')) {
            if( empty($this->request->data['type']) ){
                $this->request->data['type'] = '';
            }

            $chanel = Payment::CHANEL_MANUAL; // default
            $order_id = microtime(true) * 10000;

            $data = $this->request->data;
            $data = array_merge($data, array(
                'order_id'  => $order_id,
                'user_id'   => $user['id'],
                'game_id'   => $game['id'],
                'chanel'    => $chanel,
                'status'    => WaitingPayment::STATUS_WAIT,
                'time'      => time(),
            ));

            try {
                $orderManual = $this->CardManual->save($data);
                if( !empty($orderManual) ){
                    $this->WaitingPayment->save($data);
                    $this->view = 'order';

                    # tạo bot telegram
                    if( Configure::read('Bot.Telegram') ) {
                        $type_telegram = '';
                        switch ($this->request->data['type']) {
                            case Payment::TYPE_NETWORK_VIETTEL :
                                $type_telegram = 'Viettel';
                                break;
                            case Payment::TYPE_NETWORK_MOBIFONE :
                                $type_telegram = 'Mobifone';
                                break;
                            case Payment::TYPE_NETWORK_VINAPHONE :
                                $type_telegram = 'Vinaphone';
                                break;
                            case Payment::TYPE_NETWORK_GATE :
                                $type_telegram = 'Gate';
                                break;
                        }
                        $text_telegram = "Card seria: " . substr($this->request->data['card_serial'], 0, -3) . 'xxx' . "\n\r"
                            . "Card code: " . substr($this->request->data['card_code'], 0, -3) . 'xxx' . "\n\r"
                            . "Price: " . number_format($this->request->data['card_price'], 0, '.', ',') . ' vnđ' . "\n\r"
                            . "Type: " . $type_telegram . "\n\r"
                            . "User: " . $user['username'] . "\n\r"
                            . "Game: " . $game['title_os'] . "\n\r";

                        App::import('Lib', 'RedisQueue');
                        $Redis = new RedisQueue();
                        $redis_data = array(
                            'type' => 'TelegramSendNotify',
                            'data' => array(
                                'chat_id' => '-302159231',
                                'message' => $text_telegram
                            )
                        );
                        $Redis->rPush($redis_data);
                        unset($text_telegram);
                        unset($redis_data);
                    }
                }else{
                    $msgFlash = $this->CardManual->validationErrors;
                    $this->Session->setFlash($msgFlash, 'error', false, 'error');
                }

            } catch (Exception $e) {
                CakeLog::error($e->getMessage());
            }
        }
        end:
    }

    public function admin_index(){
        $this->layout = 'default_bootstrap';

        $this->loadModel('CardManual');
        $this->Prg->commonProcess('CardManual');
        $this->request->data['CardManual'] = $this->passedArgs;

        $parsedConditions = array();
        if(!empty($this->passedArgs)) {
            $parsedConditions = $this->CardManual->parseCriteria($this->passedArgs);
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
            'CardManual.game_id' => $this->Session->read('Auth.User.permission_game_default')
        ), $parsedConditions);

        $this->loadModel('CardManual');
        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');

        $this->CardManual->bindModel(array(
            'belongsTo' => array('Game', 'User')
        ));

        $games = $this->CardManual->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array(
                'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
            )
        ));

        $limit = 20;
        if( !empty($this->passedArgs['number']) ) $limit = $this->passedArgs['number'];
        $this->paginate = array(
            'CardManual' => array(
                'fields' => array('CardManual.*', 'User.username', 'User.id', 'Game.title', 'Game.os'),
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User'
                ),
                'order' => array('CardManual.id' => 'DESC'),
                'recursive' => -1,
                'limit' => $limit
            )
        );

        $orders = $this->paginate('CardManual');

        $status = array(
            WaitingPayment::STATUS_WAIT         => 'Create',
            WaitingPayment::STATUS_COMPLETED    => 'Success',
            WaitingPayment::STATUS_ERROR        => 'Error',
            WaitingPayment::STATUS_REVIEW       => 'Review',
        );

        $types = array(
            Payment::TYPE_NETWORK_VIETTEL       => 'Viettel',
            Payment::TYPE_NETWORK_VINAPHONE     => 'Vinaphone',
            Payment::TYPE_NETWORK_MOBIFONE      => 'Mobifone',
            Payment::TYPE_NETWORK_GATE          => 'Gate',
            Payment::TYPE_NETWORK_BANKING       => 'Banking',
            Payment::TYPE_NETWORK_CARD          => 'Card',
            Payment::TYPE_NETWORK_SMS           => 'Sms',
        );

        $this->set(compact('orders', 'games', 'status', 'types'));
    }

    public function admin_edit($id)
    {
        $this->layout = 'default_bootstrap';
        $this->loadModel('CardManual');
        $this->loadModel('WaitingPayment');
        if (!empty($id)) {
            $order = $this->CardManual->findById($id);
            if (empty($order)) {
                throw new NotFound('Không tìm thấy CardManual này');
            }
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $price_check = array(10000, 20000, 30000, 40000, 50000, 100000, 200000, 300000, 500000);
            if( !in_array($this->request->data['CardManual']['price'], $price_check)
            ){
                throw new NotFoundException('Chọn sai giá');
            }

            try {
                if ($this->CardManual->save($this->request->data)) {
                    if( $this->request->data['CardManual']['status'] == WaitingPayment::STATUS_ERROR){
                        $waiting = $this->WaitingPayment->find('first', array(
                            'conditions' => [
                                'WaitingPayment.order_id' => $order['CardManual']['order_id'],
                            ],
                        ));
                        $this->WaitingPayment->id = $waiting['WaitingPayment']['id'];
                        $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));
                    }

                    $this->Session->setFlash('CardManual has been saved');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('CardManual could not be saved. Please, try again.');
                }
            }catch (Exception $e){
                CakeLog::error('save CardManual add error: '.$e->getMessage());
                $this->Session->setFlash('CardManual could not be saved. Please, try again.');
            }
        }

        if( $id ){
            $this->request->data = $order;
        }
    }

    public function admin_publish($id){
        $this->loadModel('CardManual');
        $this->loadModel('WaitingPayment');
        if (!empty($id)) {
            $order = $this->CardManual->findById($id);
            if (empty($order)) {
                throw new NotFound('Không tìm thấy CardManual này');
            }
        }

        if( $order['CardManual']['status'] != WaitingPayment::STATUS_REVIEW ) {
            throw new NotFoundException('giao dịch đã được sử lý trước đó');
        }

        # ktra thẻ cao đã được xử lý trước, tránh trùng lặp mã thẻ
        $card_check = $this->CardManual->find('all', array(
            'fields' => array('id', 'order_id'),
            'conditions' => array(
                'CardManual.card_serial'    => $order['CardManual']['card_serial'],
                'CardManual.card_code'      => $order['CardManual']['card_code'],
                'CardManual.status'         => WaitingPayment::STATUS_COMPLETED,
            ),
            'limit' => 1
        ));

        if( !empty($card_check) ){
            throw new NotFoundException('giao dịch đã được sử lý trước đó');
        }

        # xử lý cộng coin cho giao dịch thành công
        try {
            $dataSource = $this->CardManual->getDataSource();
            $dataSource->begin();

            $this->CardManual->id = $id;
            if ( $this->CardManual->saveField('status', WaitingPayment::STATUS_COMPLETED, array('callbacks' => false))) {
                $data_payment = array(
                    'order_id' => $order['CardManual']['order_id'],
                    'user_id' => $order['CardManual']['user_id'],
                    'game_id' => $order['CardManual']['game_id'],

                    'card_code' => $order['CardManual']['card_code'],
                    'card_serial' => $order['CardManual']['card_serial'],
                    'price' => $order['CardManual']['price'],

                    'time' => $order['CardManual']['time'],
                    'type' => $order['CardManual']['type'],
                    'chanel' => $order['CardManual']['chanel'],
                    'note' => $order['CardManual']['detail'],
                );

                $waiting = $this->WaitingPayment->find('first', array(
                    'conditions' => [
                        'WaitingPayment.order_id' => $order['CardManual']['order_id'],
                    ],
                ));

                App::uses('PaymentLib', 'Payment');
                $paymentLib = new PaymentLib();

                $paymentLib->setResolvedPayment($waiting['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);


                if ($paymentLib->add($data_payment)) {
                    $this->Session->setFlash('Giao dịch đã được sử lý', 'success');
                    $dataSource->commit();
                } else {
                    $this->Session->setFlash('Lỗi xảy ra', 'error');
                    $dataSource->rollback();
                }
            } else {
                $this->Session->setFlash('Lỗi xảy ra', 'error');
                $dataSource->rollback();
            }
        }catch (Exception $e){
            $this->Session->setFlash('Lỗi xảy ra', 'error');
            CakeLog::error('manual pay - có lỗi xảy ra - ' . $e->getMessage());
            $dataSource->rollback();
        }
        $this->redirect($this->referer(array('action' => 'index'), true));
    }

    public function shopcard(){
        # load for view
        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');
        $this->loadModel('CardManual');

        $this->layout = 'payment';

        $this->Common->setTheme();
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        $role_id = $area_id = 1;
        if (!empty($this->request->query('role_id'))) $role_id = $this->request->query('role_id');
        if (!empty($this->request->query('area_id'))) $area_id = $this->request->query('area_id');

        # check đóng mở thẻ
        App::import('Lib', 'RedisQueue');
        $Redis = new RedisQueue('default');
        $Redis->key = 'payment-shop-card-status-' . Payment::TYPE_NETWORK_VIETTEL;
        $vtt_data = $Redis->lRange(0, -1);

        $Redis->key = 'payment-shop-card-status-' . Payment::TYPE_NETWORK_MOBIFONE;
        $mobi_data = $Redis->lRange(0, -1);

        $Redis->key = 'payment-shop-card-status-' . Payment::TYPE_NETWORK_VINAPHONE;
        $vina_data = $Redis->lRange(0, -1);

        $Redis->key = 'payment-shop-card-status-' . Payment::TYPE_NETWORK_GATE;
        $gate_data = $Redis->lRange(0, -1);

        $Redis->key = 'payment-shop-card-status-' . Payment::TYPE_NETWORK_VCOIN;
        $vcoin_data = $Redis->lRange(0, -1);

        $Redis->key = 'payment-shop-card-status-' . Payment::TYPE_NETWORK_ZING;
        $zig_data = $Redis->lRange(0, -1);

        $disable = array(
            Payment::TYPE_NETWORK_VIETTEL       => $vtt_data,
            Payment::TYPE_NETWORK_MOBIFONE      => $mobi_data,
            Payment::TYPE_NETWORK_VINAPHONE     => $vina_data,
            Payment::TYPE_NETWORK_GATE          => $gate_data,
            Payment::TYPE_NETWORK_VCOIN         => $vcoin_data,
            Payment::TYPE_NETWORK_ZING          => $zig_data,
        );

        $token = $this->request->header('token');
        $this->set(compact('disable', 'token', 'role_id', 'area_id'));

        $this->view = 'index';
        if( !empty($this->request->query('type')) && in_array( $this->request->query('type') ,array(
                Payment::TYPE_NETWORK_ZING, Payment::TYPE_NETWORK_GATE, Payment::TYPE_NETWORK_VCOIN
            ))){
            $this->view = 'zing';
        }

        if ($this->request->is('post')) {
            if( empty($this->request->data['type']) ){
                $this->request->data['type'] = '';
            }

            if( !empty($this->request->query('type'))
                && in_array($this->request->query('type'), array(
                    Payment::TYPE_NETWORK_VIETTEL,
                    Payment::TYPE_NETWORK_MOBIFONE,
                    Payment::TYPE_NETWORK_VINAPHONE
                ) )
            ){
                if( empty($this->request->data['card_price']) ){
                    $this->request->data['card_price'] = '';
                }
            }

            if( !empty($this->request->query('type'))){
                $this->request->data['type'] = $this->request->query('type');
            }

            $chanel = Payment::CHANEL_SHOPCARD; // default
            $order_id = microtime(true) * 10000;

            $data = $this->request->data;
            $data = array_merge($data, array(
                'order_id'  => $order_id,
                'user_id'   => $user['id'],
                'game_id'   => $game['id'],
                'chanel'    => $chanel,
                'status'    => WaitingPayment::STATUS_WAIT,
                'time'      => time(),
            ));

            if( $this->Common->bruteForce(array(
                'card_serial'   => $data['card_serial'],
                'card_code'     => $data['card_code'],
            ), 5*60, 3, true)
            ){
                $this->Session->setFlash(__("Giao dịch đang được xử lý"), 'error', false, 'error');
                goto end;
            }

            try {
                $orderManual = $this->CardManual->save($data);
                if( !empty($orderManual) ){
                    $waiting = $this->WaitingPayment->save($data);
                    # gọi lên cổng check trạng thái và chờ callback
                    $rate = 1;
                    $type = $data['type'];
                    switch ( $data['type'] ){
                        case Payment::TYPE_NETWORK_VIETTEL :
                            $type = 1;
                            break;
                        case Payment::TYPE_NETWORK_ZING :
                            $type = 2;
                            $rate = 1.3;
                            break;
                        case Payment::TYPE_NETWORK_VCOIN :
                            $type = 3;
                            $rate = 1.3;
                            break;
                        case Payment::TYPE_NETWORK_GATE :
                            $type = 4;
                            $rate = 1.3;
                            break;
                    }

                    if( in_array($type, array(2, 3, 4) ) && empty($data['card_price']) ) $data['card_price'] = 0;

                    App::uses('ShopCard', 'Payment');
                    $LibPay = new ShopCard();
                    $data_pay = array(
                        'merchant_id'       => (int)$LibPay->getMerchantId(),
                        'merchant_user'     => $LibPay->getMerchantUser(),
                        'merchant_password' => $LibPay->getMerchantPassword(),
                        'card_type'         => $type,
                        'card_amount'       => (int) $data['card_price'],
                        'card_seri'         => $data['card_serial'],
                        'card_code'         => $data['card_code'],
                        'note'              => 'muprod'
                    );
                    $result = $LibPay->checkout($data_pay);
                    CakeLog::info('shopcard - checkout:' . print_r(array($data['order_id'] => $result), true) , 'payment');
                    $this->view = 'order';
                    if( !empty($result['status']) && $result['status'] == 2){
                        $this->WaitingPayment->id = $waiting['WaitingPayment']['id'];
                        $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_COMPLETED, array('callbacks' => false));

                        App::uses('PaymentLib', 'Payment');
                        $paymentLib = new PaymentLib();
                        $data_payment = [
                            'waiting_id' => $waiting['WaitingPayment']['id'],
                            'time'       => time(),
                            'type'       => $waiting['WaitingPayment']['type'],
                            'test'       => 0,
                            'chanel'     => $waiting['WaitingPayment']['chanel'],
                            'order_id'   => $order_id,
                            'user_id'    => $user['id'],
                            'game_id'    => $game['id'],

                            'role_id' => $role_id,
                            'area_id' => $area_id,

                            'price'      => ($result['amount']) * ($rate),
                            'price_org'  => $result['amount'],
                            'price_end'  => ($result['amount']) * 0.62,
                        ];
                        $paymentLib->add($data_payment);

                        $this->view = 'success';

                        # push notify telegrame
                        if( Configure::read('Bot.Telegram') ) {
                            $text_telegram = "Type: Shopcard - " . $waiting['WaitingPayment']['type'] . "\n\r"
                                . "Order Id: " . $order_id . "\n\r"
                                . "Price: " . number_format($result['amount'], 0, '.', ',') . ' vnđ' . "\n\r"
                                . "User: " . $user['username'] . "\n\r"
                                . "Game: " . $game['title_os'] . "\n\r";
                            App::import('Lib', 'RedisQueue');
                            $Redis2 = new RedisQueue();
                            $redis_data = array(
                                'type' => 'TelegramSendNotify',
                                'data' => array(
                                    'chat_id' => '-302159231',
                                    'message' => $text_telegram
                                )
                            );
                            $Redis2->rPush($redis_data);
                            unset($text_telegram);
                            unset($redis_data);
                        }
                    }else{
                        $this->view = 'error';

                        $this->WaitingPayment->id = $waiting['WaitingPayment']['id'];
                        $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));

                        $this->CardManual->id = $orderManual['CardManual']['id'];
                        $this->CardManual->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));
                    }
                }else{
                    $msgFlash = $this->CardManual->validationErrors;
                    $this->Session->setFlash($msgFlash, 'error', false, 'error');
                }
            } catch (Exception $e) {
                CakeLog::error($e->getMessage());
                $this->view = 'error';
            }
        }

        end:
    }

    public function sweb(){
        # load for view
        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');
        $this->loadModel('CardManual');

        $this->layout = 'payment';

        $this->Common->setTheme();
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        # check đóng mở thẻ
        App::import('Lib', 'RedisQueue');
        $Redis = new RedisQueue('default');
        $Redis->key = 'payment-manual-sweb-status-' . Payment::TYPE_NETWORK_VIETTEL;
        $vtt_data = $Redis->lRange(0, -1);

        $Redis->key = 'payment-manual-sweb-status-' . Payment::TYPE_NETWORK_VINAPHONE;
        $vn_data = $Redis->lRange(0, -1);

        $Redis->key = 'payment-manual-sweb-status-' . Payment::TYPE_NETWORK_MOBIFONE;
        $mb_data = $Redis->lRange(0, -1);

        $Redis->key = 'payment-manual-sweb-status-' . Payment::TYPE_NETWORK_GATE;
        $gate_data = $Redis->lRange(0, -1);

        $disable = array(
            Payment::TYPE_NETWORK_VIETTEL       => $vtt_data,
            Payment::TYPE_NETWORK_VINAPHONE     => $vn_data,
            Payment::TYPE_NETWORK_MOBIFONE      => $mb_data,
            Payment::TYPE_NETWORK_GATE          => $gate_data,
        );

        $role_id = $area_id = 1;
        if (!empty($this->request->query('role_id'))) $role_id = $this->request->query('role_id');
        if (!empty($this->request->query('area_id'))) $area_id = $this->request->query('area_id');

        $token = $this->request->header('token');

        $this->set(compact('disable', 'token', 'role_id', 'area_id'));

        if( !empty($this->request->query('type')) && in_array( $this->request->query('type') ,array(
                Payment::TYPE_NETWORK_ZING, Payment::TYPE_NETWORK_GATE, Payment::TYPE_NETWORK_VCOIN
            ))){
            $this->view = 'zing';
        }

        if ($this->request->is('post')) {
            if( !empty($this->request->query('type'))){
                $this->request->data['type'] = $this->request->query('type');
            }
            if( empty($this->request->data['type']) ){
                $this->request->data['type'] = '';
            }

            if( !empty( $this->request->data['type'] )
                && in_array( $this->request->data['type'], array(Payment::TYPE_NETWORK_VIETTEL, Payment::TYPE_NETWORK_MOBIFONE) )
            ){
                if( empty($this->request->data['card_price']) ){
                    $this->request->data['card_price'] = '';
                }
            }

            $chanel = Payment::CHANEL_SWEB; // default
            $order_id = microtime(true) * 10000;

            $data = $this->request->data;
            $data = array_merge($data, array(
                'order_id'  => $order_id,
                'user_id'   => $user['id'],
                'game_id'   => $game['id'],
                'chanel'    => $chanel,
                'status'    => WaitingPayment::STATUS_WAIT,
                'time'      => time(),
            ));

            $Redis->key = 'payment-manual-sweb-status-' . $data['type'];
            $disable_payment = $Redis->lRange(0, -1);

            if( empty($disable_payment[0]['status']) ){
                $this->Session->setFlash(__("Nhà mạng đang bảo trì"), 'error', false, 'error');
                goto end;
            }

            if( $this->Common->bruteForce(array(
                'card_serial'   => $data['card_serial'],
                'card_code'     => $data['card_code'],
            ), 5*60, 3, true)
            ){
                $this->Session->setFlash(__("Giao dịch đang được xử lý"), 'error', false, 'error');
                goto end;
            }

            try {
                $orderManual = $this->CardManual->save($data);
                if( !empty($orderManual) ){
                    $waiting = $this->WaitingPayment->save($data);
                    # gọi lên cổng check trạng thái và chờ callback
                    $rate = 1.1;
                    $fee = 0.65;
                    $type = $data['type'];
                    switch ( $data['type'] ){
                        case Payment::TYPE_NETWORK_VIETTEL :
                            $type = 1;
                            break;
                        case Payment::TYPE_NETWORK_MOBIFONE :
                            $type = 2;
                            break;
                        case Payment::TYPE_NETWORK_VINAPHONE :
                            $type = 3;
                            break;
                        case Payment::TYPE_NETWORK_GATE :
                            $type = 4;
                            break;
                    }

                    if( $data['type'] == Payment::TYPE_NETWORK_GATE && empty($data['card_price']) ) $data['card_price'] = 0;

                    App::uses('SwebPay', 'Payment');
                    $Sweb = new SwebPay();
                    $data_pay = array(
                        'uid'           => $Sweb->getUid(),
                        'pin'           => $data['card_code'],
                        'seri'          => $data['card_serial'],
                        'price'         => (int) $data['card_price'],
                        'card_type'     => $type,
                        'note'          => 'prodmu'
                    );
                    $result = $Sweb->checkout($data_pay);
                    CakeLog::info('sweb - checkout:' . print_r($result, true) , 'payment');
                    $this->view = 'order';
                    if( isset($result['code']) ){
                        if( $result['code'] != 0 ) {
                            $this->view = 'error';

                            $this->WaitingPayment->id = $waiting['WaitingPayment']['id'];
                            $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));

                            $this->CardManual->id = $orderManual['CardManual']['id'];
                            $this->CardManual->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));
                        }

                        # xử lý cộng tiền trực tiếp cho gate
                        if ( $result['code'] == 0 && $data['type'] == Payment::TYPE_NETWORK_GATE ){
                            $this->WaitingPayment->id = $waiting['WaitingPayment']['id'];
                            $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_COMPLETED, array('callbacks' => false));

                            App::uses('PaymentLib', 'Payment');
                            $paymentLib = new PaymentLib();
                            $data_payment = [
                                'waiting_id' => $waiting['WaitingPayment']['id'],
                                'time'       => time(),
                                'type'       => $waiting['WaitingPayment']['type'],
                                'test'       => 0,
                                'chanel'     => $waiting['WaitingPayment']['chanel'],
                                'order_id'   => $order_id,
                                'user_id'    => $user['id'],
                                'game_id'    => $game['id'],

                                'role_id' => $role_id,
                                'area_id' => $area_id,

                                'price'      => ($result['info_card']) * ($rate),
                                'price_org'  => $result['info_card'],
                                'price_end'  => ($result['info_card']) * ($fee),
                            ];
                            $paymentLib->add($data_payment);

                            $this->view = 'success';

                            # push notify telegrame
                            if( Configure::read('Bot.Telegram') ) {
                                $text_telegram = "Type: Sweb - " . $waiting['WaitingPayment']['type'] . "\n\r"
                                    . "Order Id: " . $order_id . "\n\r"
                                    . "Price: " . number_format($result['info_card'], 0, '.', ',') . ' vnđ' . "\n\r"
                                    . "User: " . $user['username'] . "\n\r"
                                    . "Game: " . $game['title_os'] . "\n\r";
                                App::import('Lib', 'RedisQueue');
                                $Redis2 = new RedisQueue();
                                $redis_data = array(
                                    'type' => 'TelegramSendNotify',
                                    'data' => array(
                                        'chat_id' => '-302159231',
                                        'message' => $text_telegram
                                    )
                                );
                                $Redis2->rPush($redis_data);
                                unset($text_telegram);
                                unset($redis_data);
                            }
                        }
                    }
                }else{
                    $msgFlash = $this->CardManual->validationErrors;
                    $this->Session->setFlash($msgFlash, 'error', false, 'error');
                }
            } catch (Exception $e) {
                CakeLog::error($e->getMessage());
                $this->view = 'error';
            }
        }

        end:
    }

    public function mobo(){
        # load for view
        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');
        $this->loadModel('CardManual');

        $this->layout = 'payment';
        $this->view = 'zing';

        $this->Common->setTheme();
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        $role_id = $area_id = 1;
        if (!empty($this->request->query('role_id'))) $role_id = $this->request->query('role_id');
        if (!empty($this->request->query('area_id'))) $area_id = $this->request->query('area_id');

        # check đóng mở thẻ
        App::import('Lib', 'RedisQueue');
        $Redis = new RedisQueue('default');

        $Redis->key = 'payment-mobo-status-' . Payment::TYPE_NETWORK_GATE;
        $gate_data = $Redis->lRange(0, -1);

        $disable = array(
            Payment::TYPE_NETWORK_GATE			=> $gate_data
        );

        $token = $this->request->header('token');
        $this->set(compact('disable', 'token', 'role_id', 'area_id'));

        if ($this->request->is('post')) {
            if( !empty($this->request->query('type'))){
                $this->request->data['type'] = $this->request->query('type');
            }

            if( empty($this->request->data['type']) ){
                $this->request->data['type'] = '';
            }

            $chanel = Payment::CHANEL_MOBO; // default
            $order_id = microtime(true) * 10000;

            $data = $this->request->data;
            $data = array_merge($data, array(
                'order_id'  => $order_id,
                'user_id'   => $user['id'],
                'game_id'   => $game['id'],
                'chanel'    => $chanel,
                'type'      => Payment::TYPE_NETWORK_GATE,
                'status'    => WaitingPayment::STATUS_WAIT,
                'time'      => time(),
            ));

            if( $this->Common->bruteForce(array(
                'card_serial'   => $data['card_serial'],
                'card_code'     => $data['card_code'],
            ), 5*60, 3, true)
            ){
                $this->Session->setFlash(__("Giao dịch đang được xử lý"), 'error', false, 'error');
                goto end;
            }

            try {
                $fee = 0.75;
                $rate = 1.3;

                $orderManual = $this->CardManual->save($data);
                if( !empty($orderManual) ){
                    $waiting = $this->WaitingPayment->save($data);
                    # gọi lên cổng check trạng thái và chờ callback
                    App::uses('MoboPay', 'Payment');
                    $LibPay = new MoboPay();
                    $data_pay = array(
                        'card_seri'         => $data['card_serial'],
                        'card_code'         => $data['card_code'],
                        'transid'           => $order_id
                    );
                    $result = $LibPay->checkout($data_pay);
                    CakeLog::info('mobo - checkout:' . print_r(array($data['order_id'] => $result), true) , 'payment');
                    $this->view = 'order';
                    if( !empty($result) && $result['code'] == 0 && !empty($result['data']['result']['amount']) ){
                        $this->WaitingPayment->id = $waiting['WaitingPayment']['id'];
                        $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_COMPLETED, array('callbacks' => false));

                        App::uses('PaymentLib', 'Payment');
                        $paymentLib = new PaymentLib();
                        $data_payment = [
                            'waiting_id' => $waiting['WaitingPayment']['id'],
                            'time'       => time(),
                            'type'       => $waiting['WaitingPayment']['type'],
                            'test'       => 0,
                            'chanel'     => $waiting['WaitingPayment']['chanel'],
                            'order_id'   => $order_id,
                            'user_id'    => $user['id'],
                            'game_id'    => $game['id'],

                            'role_id' => $role_id,
                            'area_id' => $area_id,

                            'price'      => ($result['data']['result']['amount']) * ($rate),
                            'price_org'  => $result['data']['result']['amount'],
                            'price_end'  => ($result['data']['result']['amount']) * ($fee),
                        ];
                        $paymentLib->add($data_payment);

                        $this->view = 'success';

                        # push notify telegrame
                        if( Configure::read('Bot.Telegram') ) {
                            $text_telegram = "Type: Mobo - " . $waiting['WaitingPayment']['type'] . "\n\r"
                                . "Order Id: " . $order_id . "\n\r"
                                . "Price: " . number_format($result['data']['result']['amount'], 0, '.', ',') . ' vnđ' . "\n\r"
                                . "User: " . $user['username'] . "\n\r"
                                . "Game: " . $game['title_os'] . "\n\r";
                            App::import('Lib', 'RedisQueue');
                            $Redis2 = new RedisQueue();
                            $redis_data = array(
                                'type' => 'TelegramSendNotify',
                                'data' => array(
                                    'chat_id' => '-302159231',
                                    'message' => $text_telegram
                                )
                            );
                            $Redis2->rPush($redis_data);
                            unset($text_telegram);
                            unset($redis_data);
                        }
                    }else{
                        $this->view = 'error';

                        $this->WaitingPayment->id = $waiting['WaitingPayment']['id'];
                        $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));

                        $this->CardManual->id = $orderManual['CardManual']['id'];
                        $this->CardManual->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));
                    }
                }else{
                    $msgFlash = $this->CardManual->validationErrors;
                    $this->Session->setFlash($msgFlash, 'error', false, 'error');
                }
            } catch (Exception $e) {
                CakeLog::error($e->getMessage());
                $this->view = 'error';
            }
        }

        end:
    }

    public function admin_status(){
        $this->loadModel('Payment');
        $this->layout = 'default_bootstrap';

        App::import('Lib', 'RedisQueue');
        $Redis = new RedisQueue('default');

        $message = array();

        $key = 'payment-manual-sweb-status-';
        if( $this->request->query('channel') == 'mobo') $key = 'payment-mobo-status-';
        if( $this->request->query('channel') == 'shopcard') $key = 'payment-shop-card-status-';

        $Redis->key = $key . $this->request->query('type');
        $Redis->delete();
        $Redis->rPush( array('status' => $this->request->query('status')) );

        $this->set('message', $message);
        $this->view = 'admin_sweb_status';
    }
}
