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
    
    public function admin_index()
    {
        $this->Prg->commonProcess();

        $this->request->data['CompensePayment'] = $this->passedArgs;

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
        $this->loadModel('CompensePayment');
        if (!$id || !$compense = $this->CompensePayment->findById($id)) {
            throw new NotFoundException('Không tìm thấy giao dịch này');
        }

        if( !empty($compense['CompensePayment']['status']) ) {
            throw new NotFoundException('Đã thực hiện bù giao dịch này');
        }

        # tìm giao dịch waiting theo order_id
        $this->loadModel('WaitingPayment');
        $this->WaitingPayment->recursive = -1;
        $wating = $this->WaitingPayment->findByOrderIdAndStatus( $compense['CompensePayment']['order_id'], array(WaitingPayment::STATUS_QUEUEING, WaitingPayment::STATUS_ERROR) );
        if( empty($wating) ) {
            $msgFlash = "Order Id:" . $compense['CompensePayment']['order_id'] . " - không tìm thấy giao dịch hoặc giao dịch đã được bù - vui lòng kiểm tra lại";
            $this->Session->setFlash($msgFlash, 'error');
            $this->redirect($this->referer(array('action' => 'index'), true));
        }

        # check thẻ tecos đã thành công
        if( !empty($wating['WaitingPayment']['card_code']) && $wating['WaitingPayment']['card_serial'] ){
            $wating2 = $this->WaitingPayment->findByCardSerialAndCardCodeAndStatus( $wating['WaitingPayment']['card_serial'], $wating['WaitingPayment']['card_code'], WaitingPayment::STATUS_COMPLETED );
            if(!empty($wating2)){
                $msgFlash = "card serial:" . $wating['WaitingPayment']['card_serial'] . " đã được sử dụng - vui lòng kiểm tra lại";
                $this->Session->setFlash($msgFlash, 'error');
                $this->redirect($this->referer(array('action' => 'index'), true));
            }
        }

        $dataSource = $this->CompensePayment->getDataSource();
        $dataSource->begin();

        $this->CompensePayment->id = $id;
        if ($this->CompensePayment->publish($id)) {
            App::uses('PaymentLib', 'Payment');
            $paymentLib = new PaymentLib();

            $paymentLib->setResolvedPayment($wating['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);

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

    public function admin_add($id = null)
    {
        if (!empty($id)) {
            $compense = $this->CompensePayment->findById($id);
            if (empty($compense)) {
                throw new NotFound('Không tìm thấy Compense Payment này');
            }
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if( empty($this->request->data['CompensePayment']['order_id']) ){
                throw new NotFoundException('Chưa chọn giao dịch');
            }

            $price_check = array(10000, 20000, 30000, 40000, 50000, 100000, 200000, 300000, 500000);
            if( empty($this->request->data['CompensePayment']['price'])
                || !in_array($this->request->data['CompensePayment']['price'], $price_check)
            ){
                throw new NotFoundException('Chọn sai giá');
            }

            # tìm giao dịch waiting theo order_id
            $this->loadModel('WaitingPayment');
            $this->WaitingPayment->recursive = -1;
            $wating = $this->WaitingPayment->findByOrderId($this->request->data['CompensePayment']['order_id']);

            if( empty($wating) ){
                throw new NotFoundException('Không tìm thấy giao dịch phù hợp');
            }

            $data = array(
                'CompensePayment' => array(
                    'order_id'  => $this->request->data['CompensePayment']['order_id'],
                    'user_id'   => $wating['WaitingPayment']['user_id'],
                    'game_id'   => $wating['WaitingPayment']['game_id'],
                    'price'     => $this->request->data['CompensePayment']['price'],
                    'type'      => $wating['WaitingPayment']['type'],
                    'chanel'    => $wating['WaitingPayment']['chanel'],
                    'last_user' => $this->Auth->user('username'),
                    'description'    => $this->request->data['CompensePayment']['description']
                )
            );

            if (!empty($id)) {
                $data['CompensePayment']['id'] = $compense['CompensePayment']['id'];
            }

            try {
                if ($this->CompensePayment->save($data)) {
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

        if( $id ){
            $this->request->data = $compense;
        }

        $this->view = 'admin_add';
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
}
?>