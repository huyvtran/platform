<?php
App::uses('AppController', 'Controller');

class CompensePaymentsController extends AppController {

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'default_bootstrap';

        if (!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            $this->CompensePayment->enablePublishable('find', false);
        }
    }

    public $components = array(
        'Search.Prg'
    );

    public function admin_add($id = null)
    {
        if ($this->request->is('post') || $this->request->is('put')) {

            # Check user existed
            $this->CompensePayment->User->recursive = -1;
            $user_existed = $this->CompensePayment->User->findById($this->request->data['CompensePayment']['user_id']);
            if(empty($user_existed)){
                throw new NotFoundException('Không tìm thấy User này');
            }

            $price_check = array(10000, 20000, 30000, 50000, 100000, 200000, 300000, 500000);
            if( empty($this->request->data['CompensePayment']['price'])
                || !in_array($this->request->data['CompensePayment']['price'], $price_check)
            ){
                throw new NotFoundException('Chọn sai giá');
            }

            try {
                $this->request->data['CompensePayment']['last_user'] = $this->Auth->user('username');
                if( empty($id) ) $this->request->data['CompensePayment']['order_id'] = microtime(true) * 10000 ;

                if ($this->CompensePayment->save($this->request->data)) {
                    $this->Session->setFlash('Compense Payment has been saved');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Compense Payment could not be saved. Please, try again.');
                }
            }catch (Exception $e){
                CakeLog::error('save Compense Payment add error: '.$e->getMessage());
                $this->Session->setFlash('Compense Payment could not be saved. Please, try again.');
            }
        }

        if (!empty($id)) {
            $compense = $this->CompensePayment->findById($id);
            if (empty($compense)) {
                throw new NotFound('Không tìm thấy Compense Payment này');
            }
            $this->request->data = $compense;
        }

        $games = $this->CompensePayment->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array(
                'Game.id' => $this->Session->read('Auth.User.permission_game_default')
            )
        ));

        $this->set(compact('games'));
        $this->view = 'admin_add';

        # set contain in view
        $this->loadModel('Payment');
    }

    public function admin_edit($id){
        $this->CompensePayment->recursive = -1;
        if (!$id || !$compense = $this->CompensePayment->findById($id)) {
            throw new NotFoundException('Không tìm thấy giao dịch này');
        }
        if( !empty($compense['CompensePayment']['status'])){
            throw new NotFoundException('Giao dịch đã được bù');
        }

        $this->admin_add($id);
    }
    
    public function admin_index()
    {
        $this->Prg->commonProcess();
        if ($this->CompensePayment->Behaviors->loaded('Searchable')) {
            $parsedConditions = $this->CompensePayment->parseCriteria($this->passedArgs);
        } else {
            $parsedConditions = array();
        }
        if (	!empty($this->passedArgs)
            &&	empty($parsedConditions)
        ) {
            if (	(count($this->passedArgs) == 1 && empty($this->passedArgs['page']))
            ) {
                $this->Session->setFlash("Can not find anyone match this conditions", "error");
            }
        }

        $games = $this->CompensePayment->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array(
                'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
            )
        ));

        $this->paginate = array(
            'CompensePayment' => array(
                'fields' => array('CompensePayment.*', 'User.username', 'User.id', 'Game.title', 'Game.os'),
                'conditions'    => $parsedConditions,
                'order'         => array('CompensePayment.id' => 'DESC'),
                'contain'       => array('Game', 'User'),
                'recursive'     => -1
            )
        );

        $compense = $this->paginate();

        $this->set(compact('games', 'compense'));

        # set contain in view
        $this->loadModel('Payment');
    }



    public function admin_compense($id)
    {
        if (!$id || !$compense = $this->CompensePayment->findById($id)) {
            throw new NotFoundException('Không tìm thấy giao dịch này');
        }

        if( !empty($compense['CompensePayment']['status']) ) {
            throw new NotFoundException('Đã thực hiện bù giao dịch này');
        }

        $dataSource = $this->CompensePayment->getDataSource();
        $dataSource->begin();

        $this->CompensePayment->id = $id;
        if ($this->CompensePayment->publish($id)) {
            App::uses('PaymentLib', 'Payment');
            $paymentLib = new PaymentLib();
            $data_payment = array(
                'time'  => time(),
                'type'  => $compense['CompensePayment']['type'],
                'chanel'    => $compense['CompensePayment']['chanel'],
                'note'      => 'Bù giao dịch',

                'order_id'  => $compense['CompensePayment']['order_id'],
                'user_id' 	=> $compense['CompensePayment']['user_id'],
                'game_id' 	=> $compense['CompensePayment']['game_id'],

                'card_code' => $compense['CompensePayment']['card_code'],
                'price'     => $compense['CompensePayment']['price'],
                'card_serial'   => $compense['CompensePayment']['card_serial']
            );
            if( $paymentLib->add($data_payment) ){
                $this->Session->setFlash('Giao dịch đã được bù', 'success');
                $dataSource->commit();
            }else{
                $this->Session->setFlash('Lỗi xảy ra', 'error');
                $dataSource->rollback();
            }
        } else {
            $this->Session->setFlash('Lỗi xảy ra', 'error');
            $dataSource->rollback();
        }
        $this->redirect($this->referer(array('action' => 'index'), true));
    }
}
?>