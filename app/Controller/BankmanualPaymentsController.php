<?php

App::uses('AppController', 'Controller');

class BankmanualPaymentsController extends AppController {
    public $components = array(
        'Search.Prg'
    );

	public function beforeFilter()
	{
		parent::beforeFilter();
        $this->Auth->allow(
            'index', 'profile', 'orders', 'success', 'detail'
        );
	}

    public function index(){
        $this->layout = 'angular';
        $this->Common->currentGame();
    }

    public function profile(){
        $game = $this->Common->currentGame();

        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            throw new NotFoundException(__('Vui lòng login'));
        }
        $user = $this->Auth->user();
        // lấy thông tin profile
        $this->loadModel('Profile');
        $profile = $this->Profile->findByUserId($user['id']);

        $this->layout = 'blank';
        $this->Common->currentGame();
        $this->set(compact('profile'));
    }

    public function orders(){
        $game = $this->Common->currentGame();

        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            throw new NotFoundException(__('Vui lòng login'));
        }
        $user = $this->Auth->user();

        // lưu thông tin thanh toán
        $this->request->data = Hash::merge($this->request->data, array('user_id' => $user['id']));
        $this->loadModel('Profile');
        $profile = $this->Profile->findByUserId($user['id']);
        if( empty($profile['Profile']['id']) ) $this->Profile->create();
        else $this->Profile->id = $profile['Profile']['id'];
        $this->Profile->save($this->request->data, false);

        $this->layout = 'blank';
        $this->Common->currentGame();
        $this->set(compact('profile'));
        $this->autoRender = false;
    }

    public function detail(){
        $game = $this->Common->currentGame();

        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            throw new NotFoundException(__('Vui lòng login'));
        }
        $user = $this->Auth->user();

        $this->loadModel('Profile');
        $profile = $this->Profile->findByUserId($user['id']);

        if( empty($profile) ){
            throw new NotFoundException(__('Vui lòng cập nhật thông tin'));
        }

        $productId = $this->request->query('productId');
        $this->loadModel('Product');
        $this->Product->recursive = -1;
        $product = $this->Product->findByIdAndGameId($productId, $game['id']);

        if( empty($product) ){
            throw new NotFoundException(__('Không có gói xu phù hợp'));
        }

        // Tạo giao dịch
        $this->loadModel('Payment');
        $this->loadModel('WaitingPayment');
        $this->loadModel('BankManual');

        $order_id = "B" . microtime(true) * 10000;
        $chanel = Payment::CHANEL_MANUAL;
        $type = Payment::TYPE_NETWORK_BANKING;
        $data = array(
            'buyer_name'    => $profile['Profile']['fullname'],
            'buyer_phone'   => $profile['Profile']['phone'],
            'buyer_email'   => $profile['Profile']['email'],

            'order_id'  => $order_id,
            'user_id'   => $user['id'],
            'game_id'   => $game['id'],
            'price'     => $product['Product']['platform_price'],
            'status'    => WaitingPayment::STATUS_WAIT,
            'time'      => time(),
            'type'      => $type,
            'chanel'    => $chanel,
        );

        $dataSource = $this->BankManual->getDataSource();
        $dataSource->begin();
        try {
            $this->WaitingPayment->save($data);
            $order = $this->BankManual->save($data);
            if ( !empty( $order) ){
                $dataSource->commit();
            }
        }catch (Exception $e){
            $dataSource->rollback();
        }
        $this->layout = 'blank';
        $this->Common->currentGame();

        $this->set(compact('profile', 'order' , 'product'));
    }

	public function pay(){
        $game = $this->Common->currentGame();

        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            throw new NotFoundException(__('Vui lòng login'));
        }
        $token = $this->request->header('token');

        $this->loadModel('Payment');
        $this->loadModel('Product');
        $products = $this->Product->find('all', array(
            'conditions' => array(
                'Product.game_id'   => $game['id'],
                'Product.chanel'    => Payment::CHANEL_PAYPAL,
            ),
            'order'     => array('Product.platform_price' => 'asc' ),
            'recursive' => -1
        ));

        $this->set(compact('products', 'game', 'token'));
        $this->layout = 'payment';
    }

    public function admin_index(){
        $this->layout = 'default_bootstrap';

        $this->loadModel('WaitingPayment');
        $this->loadModel('BankManual');
        $this->Prg->commonProcess('BankManual');
        $this->request->data['BankManual'] = $this->passedArgs;

        $parsedConditions = array();
        if(!empty($this->passedArgs)) {
            $parsedConditions = $this->BankManual->parseCriteria($this->passedArgs);
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
            'BankManual.game_id' => $this->Session->read('Auth.User.permission_game_default')
        ), $parsedConditions);

        $this->BankManual->bindModel(array(
            'belongsTo' => array('Game', 'User')
        ));

        $games = $this->BankManual->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array(
                'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
            )
        ));

        $limit = 20;
        if( !empty($this->passedArgs['number']) ) $limit = $this->passedArgs['number'];
        $this->paginate = array(
            'BankManual' => array(
                'fields' => array('BankManual.*', 'User.username', 'User.id', 'Game.title', 'Game.os'),
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User'
                ),
                'order' => array('BankManual.id' => 'DESC'),
                'recursive' => -1,
                'limit' => $limit
            )
        );

        $orders = $this->paginate('BankManual');

        $status = array(
            WaitingPayment::STATUS_WAIT         => 'Create',
            WaitingPayment::STATUS_QUEUEING     => 'Wait',
            WaitingPayment::STATUS_COMPLETED    => 'Success',
            WaitingPayment::STATUS_ERROR        => 'Error',
        );

        $this->set(compact('orders', 'games', 'status'));
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

}
