<?php
App::uses('AppController', 'Controller');

class CompensePaymentsController extends AppController {
    public $components = array('Paginator', 'Search.Prg',);

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'default_bootstrap';

    }

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
            debug($this->request->data['CompensePayment']['price']);die;
            try {
                if ($this->CompensePayment->save($this->request->data)) {
                    $this->Session->setFlash('Compense Payment has been saved');
                } else {
                    $this->Session->setFlash('Compense Payment could not be saved. Please, try again.');
                }
            }catch (Exception $e){
                CakeLog::error('save Compense Payment add error: '.$e->getMessage());
                $this->Session->setFlash('Compense Payment could not be saved. Please, try again.');
            }
            $this->redirect(array('action' => 'index'));
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
    }
    
    public function admin_index()
    {
        $this->CompenseOrder->recursive = 0;
        $this->Prg->commonProcess();
        $this->request->data['CompenseOrder'] = $this->passedArgs;

        if ($this->CompenseOrder->Behaviors->loaded('Searchable')) {
            $parsedConditions = $this->CompenseOrder->parseCriteria($this->passedArgs);
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

        $permissionCompense = array();
        if ($this->Auth->loggedIn()) {
            if (!in_array($this->Auth->user('role'), array('Guest', 'User'))) {
                $this->loadModel('Permission');
                if (in_array( $this->Auth->user('role'), array('Admin', 'Developer') )) {
                    $this->loadModel('Game');
                    $permissionCompense = $this->Game->find('list', array('fields' => array('id', 'id')));
                } else {
                    $permissionCompense = $this->Permission->getRightIds('Game', $this->Auth->user('id'), 'compense');
                }
            }
        }

        $this->loadModel('Game');
        $games = $this->Game->find('list', array(
            'fields' => array('app_key', 'title_os'),
            'conditions' => array(
                'Game.id' => $permissionCompense,
            )
        ));
        $list_app_key = array_keys($games);
        $parsedConditions = Hash::merge(array("CompenseOrder.app_key" => $list_app_key) , (array) $parsedConditions);

        $this->CompenseOrder->bindModel(array(
            'belongsTo' => array(
                'Game' => array(
                    'foreignKey' => false,
                    'conditions' => array('CompenseOrder.app_key = Game.app_key')
                )
            )
        ));

        $this->paginate = array(
            'CompenseOrder' => array(
                'conditions' => $parsedConditions,
                'order' => array('CompenseOrder.id' => 'DESC'),
                'contain' => array('Product', 'Game', 'User')
            )
        );

        $compenseOrders = $this->Paginator->paginate();

        $this->set(compact('games'));
        $this->set('compenseOrders', $compenseOrders);
    }



    public function admin_compense($id)
    {
        if (!$id || !$compenseOrder = $this->CompenseOrder->findById($id)) {
            throw new NotFoundException('Không tìm thấy giao dịch này');
        }

        if(!empty($compenseOrder['CompenseOrder']['status'])) throw new NotFoundException('Đã thực hiện bù giao dịch này');

        $user_id      = $compenseOrder['CompenseOrder']['user_id'];
        $app_key      = $compenseOrder['CompenseOrder']['app_key'];
        $game_role_id = $compenseOrder['CompenseOrder']['game_role_id'];
        $game_area_id = $compenseOrder['CompenseOrder']['game_area_id'];
        $product_id   = $compenseOrder['CompenseOrder']['product_id'];

        $dataSource = $this->CompenseOrder->getDataSource();
        $dataSource->begin();

        $this->CompenseOrder->id = $id;
        if ($this->CompenseOrder->saveField('status', 1) &&
            $this->CompenseOrder->saveField('last_user', $this->Auth->user('username'))
        ) {
            try {
                if ($compenseOrder['CompenseOrder']['payment_type'] == CompenseOrder::TYPE_DIRECT) {
                    $mobComp = new MobCompensate();
                    $result = $mobComp->createFromProduct($user_id, $app_key, $game_role_id, $game_area_id, $product_id, CompenseOrder::TYPE_COMPENSE);
                    if (isset($result['status']) && $result['status'] == 0) {
                        $this->Session->setFlash('đã đền bù giao dịch này', 'success');
                    } else {
                        $this->Session->setFlash('Có lỗi xảy ra');
                    }
                } else if ($compenseOrder['CompenseOrder']['payment_type'] == CompenseOrder::TYPE_THROUGH_WEB) {
                    $this->loadModel('PaymentWeb');
                    $this->loadModel('Product');
                    $this->loadModel('Game');
                    $this->PaymentWeb->validator()->remove('card_type')->remove('card_number')->remove('card_serial');
                    $game_id = $this->Game->find('first', array('conditions' => array('app_key' => $app_key), 'recursive' => -1));
                    $product = $this->Product->find('first', array('conditions' => array('id' => $product_id), 'recursive' => -1));
                    $amount = str_replace(array("(",")"), array("-",""), $product['Product']['price']);
                    $data_save = array(
                        'order_id'     => microtime(true) * 10000,
                        'payment_type' => $product['Product']['payment_type'],
                        'user_id'      => $user_id,
                        'game_id'      => $game_id['Game']['id'],
                        'game_role_id' => $game_role_id,
                        'game_area_id' => $game_area_id,
                        'status_payment' => PaymentWeb::STATUS_PAYMENT_SUCCESS,
                        'status_use' => PaymentWeb::STATUS_USE_WAITING,
                        'amount_pay' => $amount,
                        'source_type' => CompenseOrder::TYPE_COMPENSE,
                    );
                    $this->PaymentWeb->create();
                    if ($this->PaymentWeb->save($data_save)) {
                        $this->Session->setFlash('Đã đền bù giao dịch này', 'success');
                    } else {
                        $this->Session->setFlash('Có lỗi xảy ra');
                    }
                } else {
                    $this->Session->setFlash('Có lỗi xảy ra, vui lòng thử lại', 'error');
                }
            }catch (Exception $e){
                CakeLog::error('action compense error: '.$e->getMessage());
            }
        } else {
            $this->Session->setFlash('Có lỗi xảy ra, không update được thông tin đền bù');
        }
        $dataSource->commit();
        $this->redirect($this->referer(array('action' => 'index'), true));
    }

    public function admin_edit($id){
        if ( !$id ) {
            throw new NotFoundException('Không tìm thấy giao dịch này');
        }

        $this->CompenseOrder->bindModel(array(
            'belongsTo' => array(
                'Game' => array(
                    'foreignKey' => false,
                    'conditions' => array('CompenseOrder.app_key = Game.app_key')
                )
            )
        ));
        $compenseOrder = $this->CompenseOrder->find('first', array(
            'conditions' => array(
                "CompenseOrder.id" => $id
            ),
            'contain'=> array( 'Game' )
        ));

        if ( !$compenseOrder ) {
            throw new NotFoundException('Không tìm thấy giao dịch này');
        }

        if(!empty($compenseOrder['CompenseOrder']['status'])) throw new NotFoundException('Đã thực hiện bù giao dịch này, không được edit');

        if ($this->request->is('post') || $this->request->is('put')) {
            if(empty($this->request->data['CompenseOrder']['app_key'])){
                throw new NotFoundException('Không tìm thấy game này');
            }
            if(empty($this->request->data['CompenseOrder']['user_id'])){
                throw new NotFoundException('chưa nhập User id này');
            }

            if ($this->Auth->user('username')) {
                $this->request->data['CompenseOrder']['last_user'] = $this->Auth->user('username');
            }

            try {
                if ($this->CompenseOrder->save($this->request->data)) {
                    $this->Session->setFlash('has been saved', 'success');
                    $this->redirect(array('action' => 'admin_edit', $id));
                } else {
                    $this->Session->setFlash('Compense Order could not be saved. Please, try again.','error');
                }
            }catch (Exception $e){
                CakeLog::error('save CompenseOrder edit error: '.$e->getMessage());
                $this->Session->setFlash('Compense Order could not be saved. Please, try again.');
            }
        }

        $this->loadModel('Product');
        $products = $this->Product->find('list', array(
            'conditions' => array(
                'Product.game_id' => $compenseOrder['Game']['id']
            ),

            'contain' => false,
            'fields' => array('Product.id','Product.title')
        ));
        $this->request->data = $compenseOrder;
        $this->set('products', $products);
    }
}
?>