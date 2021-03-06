<?php

App::uses('AppController', 'Controller');

class WaitingPaymentsController extends AppController {
	public function beforeFilter()
	{
		parent::beforeFilter();
	}

    public $components = array(
        'Search.Prg'
    );

    public function admin_index(){
        $this->layout = 'default_bootstrap';

        $this->Prg->commonProcess();
        $this->request->data['WaitingPayment'] = $this->passedArgs;

        $parsedConditions = array();
        if(!empty($this->passedArgs)) {
            $parsedConditions = $this->WaitingPayment->parseCriteria($this->passedArgs);
        }

        if( !empty($this->passedArgs) && empty($parsedConditions)
        ){
            if (	(count($this->passedArgs) == 1 && empty($this->passedArgs['page']))
                ||	count($this->passedArgs) > 1
            ) {
                $this->Session->setFlash("Can not find anyone match this conditions", "error");
            }
        }

        $this->loadModel('Payment');

        if($this->Auth->User('username') == 'cskh1pay'){
            $parsedConditions = array_merge(array(
                'WaitingPayment.chanel' => array(Payment::CHANEL_ONEPAY, Payment::CHANEL_ONEPAY_2)
            ), $parsedConditions);
        }else{
            $parsedConditions = array_merge(array(
                'WaitingPayment.game_id' => $this->Session->read('Auth.User.permission_game_default')
            ), $parsedConditions);
        }

        $this->WaitingPayment->bindModel(array(
            'hasOne' => array(
                'Payment' => array(
                    'foreignKey' => false,
                    'conditions' => array_merge(
                        array('WaitingPayment.order_id = Payment.order_id')
                    )
                ),
            ),
            'belongsTo' => array('Game', 'User')
        ));

        $games = $this->WaitingPayment->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array(
                'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
            )
        ));

        $limit = 20;
        if( !empty($this->passedArgs['number']) ) $limit = $this->passedArgs['number'];
        $this->paginate = array(
            'WaitingPayment' => array(
                'fields' => array('WaitingPayment.*','Payment.*', 'User.username', 'User.id', 'Game.title', 'Game.os'),
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User', 'Payment'
                ),
                'order' => array('WaitingPayment.id' => 'DESC'),
                'recursive' => -1,
                'limit' => $limit
            )
        );

        $orders = $this->paginate();

        $status = array(
            WaitingPayment::STATUS_WAIT         => 'Create',
            WaitingPayment::STATUS_QUEUEING     => 'Wait',
            WaitingPayment::STATUS_COMPLETED    => 'Success',
            WaitingPayment::STATUS_ERROR        => 'Error',
            WaitingPayment::STATUS_REVIEW       => 'Review',
        );

        $chanels = array(
            Payment::CHANEL_PAYPAL      => 'Paypal',
			Payment::CHANEL_PAYPAL2     => 'Paypal 2',
			Payment::CHANEL_PAYPAL3     => 'Paypal 3',
            Payment::CHANEL_PAYPAL4     => 'Paypal 4',
            Payment::CHANEL_ONEPAY      => '1Pay',
            Payment::CHANEL_ONEPAY_2    => '1Pay 2',
            Payment::CHANEL_PAYMENTWALL => 'PaymentWall',
            Payment::CHANEL_NL_ALE      => 'Ale/NL',
            Payment::CHANEL_GOOGLE      => 'Google',
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

        if($this->Auth->User('role') == 'Distributor'){
            $chanels = array(
                Payment::CHANEL_PAYPAL      => 'Paypal',
                Payment::CHANEL_PAYPAL2     => 'Paypal 2',
                Payment::CHANEL_PAYPAL3     => 'Paypal 3',
                Payment::CHANEL_PAYPAL4     => 'Paypal 4'
            );

            $types = array(
                Payment::TYPE_NETWORK_GATE          => 'Gate',
                Payment::TYPE_NETWORK_BANKING       => 'Banking',
                Payment::TYPE_NETWORK_CARD          => 'Card',
            );
        }

        $this->set(compact('orders', 'games', 'status', 'chanels', 'types'));
    }

    public function api_index(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            $this->view = 'error';
            $result = array(
                'status' => 1,
                'message' => 'error',
            );
            goto end;
        }
        $user = $this->Auth->user();

        $this->loadModel('WaitingPayment');
        $this->loadModel('Payment');
        $gameIds = $this->Payment->Game->getSimilarGameId($game);

        $limit = 20;
        $page = 1;
        if ( !empty($this->request->query('page')) ){
            $page = $this->request->query('page');
        }
        $offset = ($page - 1)* $limit;

        $this->WaitingPayment->bindModel(array(
            'hasOne' => array(
                'Payment' => array(
                    'foreignKey' => false,
                    'conditions' => array_merge(
                        array('WaitingPayment.order_id = Payment.order_id')
                    )
                )
            )
        ));
        $data = $this->WaitingPayment->find('all', array(
            'fields'     => array('WaitingPayment.*', 'Payment.type', 'Payment.price' ),
            'conditions' => array(
                'WaitingPayment.game_id'   => $gameIds,
                'WaitingPayment.user_id'   => $user['id']

            ),
            'contain'   => array('Payment'),
            'recursive' => -1,
            'limit'     => $limit,
            'offset'    => $offset,
            'order'     => array('WaitingPayment.id desc')
        ));

        $this->set(compact('data', 'user'));

        $data_tmp = array();
        foreach ($data as $item){
            $item_status = "";
            if( isset($item['WaitingPayment']['status']) ){
                switch ( $item['WaitingPayment']['status'] ){
                    case WaitingPayment::STATUS_WAIT :
                        $item_status = "Create";
                        break;
                    case WaitingPayment::STATUS_QUEUEING :
                        $item_status = "Wait";
                        break;
                    case WaitingPayment::STATUS_COMPLETED :
                        $item_status = "Success";
                        break;
                    case WaitingPayment::STATUS_ERROR :
                        $item_status = "Error";
                        break;
                }
            }

            $item_price = 0;
            if( !empty($item['Payment']['price']) ) $item_price = $item['Payment']['price'];

            $item_type = '';
            if( !empty($item['WaitingPayment']['type']) ){
                $item_type = $this->Payment->convertType( $item['WaitingPayment']['type'] );
            }

            $tmp = array(
                'id'        => $item['WaitingPayment']['id'],
                'order_id'  => $item['WaitingPayment']['order_id'],
                'status'    => $item_status,
                'card_code' => $item['WaitingPayment']['card_code'],
                'card_serial'   => $item['WaitingPayment']['card_serial'],
                'price'         => $item_price,
                'type'          => $item_type,
                'created'       => $item['WaitingPayment']['created'],
                'modified'      => $item['WaitingPayment']['modified']
            );

            $data_tmp[] = $tmp;
        }

        $result = array(
            'status' => 0,
            'message' => 'success',
            'data' => $data_tmp
        );

        end:
        if (!empty($this->request->params['ext']) && $this->request->params['ext'] == 'json') {
            $this->set('result', $result);
            $this->set('_serialize', 'result');
        }else{
            $this->layout = 'payment';
            $this->Common->setTheme();
        }
    }

    public function admin_block_ip(){
        App::import('Lib', 'RedisQueue');
        $Redis = new RedisQueue('default', 'payment-ip-black-list');

        if( !empty($this->passedArgs['ip']) ){
            $ip = $this->passedArgs['ip'];
            $Redis->lRemove($ip);
            $Redis->rPush($ip);
        }

        $data = $Redis->lRange(0, 1000);
        $this->set(compact('data'));
        $this->layout = 'default_bootstrap';
    }

    public function admin_google(){
        $this->layout = 'default_bootstrap';

        $this->loadModel('GoogleInappOrder');
        $this->Prg->commonProcess('GoogleInappOrder');
        $this->request->data['GoogleInappOrder'] = $this->passedArgs;

        $parsedConditions = array();
        if(!empty($this->passedArgs)) {
            $parsedConditions = $this->GoogleInappOrder->parseCriteria($this->passedArgs);
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
            'GoogleInappOrder.game_id' => $this->Session->read('Auth.User.permission_game_default')
        ), $parsedConditions);

        $this->GoogleInappOrder->bindModel(array(
            'belongsTo' => array('Game', 'User',
                'Product' => array(
                    'foreignKey' => false,
                    'conditions' => array(
                        array('GoogleInappOrder.google_product_id = Product.appleid')
                    )
                )
            )
        ));

        $games = $this->GoogleInappOrder->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array(
                'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
                'Game.status' => 1
            )
        ));

        $this->paginate = array(
            'GoogleInappOrder' => array(
                'fields' => array('GoogleInappOrder.id', 'GoogleInappOrder.order_id', 'GoogleInappOrder.google_order_id', 'GoogleInappOrder.status',
                    'GoogleInappOrder.device_id','GoogleInappOrder.google_product_id', 'GoogleInappOrder.ip', 'GoogleInappOrder.created',
                    'User.username', 'User.id', 'Game.title', 'Game.os', 'Product.title'
                ),
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User', 'Product'
                ),
                'order' => array('GoogleInappOrder.id' => 'DESC'),
                'recursive' => -1,
                'limit' => 20
            )
        );

        $orders = $this->paginate('GoogleInappOrder');
        $this->set(compact('orders', 'games'));
    }

    public function admin_block( $order_id ){
        $this->loadModel('WaitingPayment');
        $this->WaitingPayment->bindModel(array(
            'hasOne' => array(
                'Payment' => array(
                    'foreignKey' => false,
                    'conditions' => array_merge(
                        array('WaitingPayment.order_id = Payment.order_id')
                    )
                ),
                'GoogleInappOrder' => array(
                    'foreignKey' => false,
                    'conditions' => array_merge(
                        array('WaitingPayment.order_id = GoogleInappOrder.order_id')
                    )
                ),
            )
        ));

        $this->WaitingPayment->recursive = 0;
        $order = $this->WaitingPayment->findByOrderId($order_id);
        if (empty($order)) {
            throw new NotFoundException('Invalid order');
        }

        if( !empty($order['GoogleInappOrder']['id']) ){
            if( !empty($order['GoogleInappOrder']['ip']) ) {
                $ip = $order['GoogleInappOrder']['ip'];
                App::import('Lib', 'RedisQueue');
                $Redis = new RedisQueue('default', 'payment-ip-black-list');
                $Redis->lRemove($ip);
                $Redis->rPush($ip);
            }

            $this->WaitingPayment->GoogleInappOrder->id = $order['GoogleInappOrder']['id'];
            $this->WaitingPayment->GoogleInappOrder->saveField('status', WaitingPayment::STATUS_REFUND, array('callbacks' => false));
        }

        if( !empty($order['Payment']) ){
            $this->WaitingPayment->Payment->id = $order['Payment']['id'];
            $this->WaitingPayment->Payment->saveField('test', 2, array('callbacks' => false));
        }

        if( !empty($order['WaitingPayment']) ){
            $this->WaitingPayment->id = $order['WaitingPayment']['id'];
            $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_REFUND, array('callbacks' => false));
        }

        $this->redirect( array(
            'controller' => 'WaitingPayments',
            'action'    => 'google'
        ) );
    }

    public function api_gift(){
        $this->Common->setTheme();
        $this->layout = 'payment';

        $game = $this->Common->currentGame();
        if (empty($game) || !$this->Auth->loggedIn()) {
            CakeLog::error('Invalid token or appkey', 'payment');
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        #check giao dịch, nếu có show giftcode
        $this->loadModel('Payment');
        $this->Payment->recursive = -1;
        $order = $this->Payment->findByUserId($user['id'], array('id', 'user_id'));
        $this->set(compact('order'));
    }
}
