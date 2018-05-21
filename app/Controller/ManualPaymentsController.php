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
        $this->render('/Payments/pay');

        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        if ($this->request->is('post')) {
            $this->render('/Payments/order');

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
                $this->set(compact('orderManual'));
            } catch (Exception $e) {
                CakeLog::error($e->getMessage());
            }
        }
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

                $wating = $this->WaitingPayment->save($data_payment);

                App::uses('PaymentLib', 'Payment');
                $paymentLib = new PaymentLib();

                $paymentLib->setResolvedPayment($wating['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);


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
