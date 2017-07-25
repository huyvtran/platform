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

        $parsedConditions = array_merge(array(
            'WaitingPayment.game_id' => $this->Session->read('Auth.User.permission_game_default')
        ), $parsedConditions);

        $this->WaitingPayment->bindModel(array(
            'hasOne' => array(
                'Payment' => array(
                    'foreignKey' => false,
                    'conditions' => array_merge(
                        array('WaitingPayment.order_id = Payment.order_id')
                    )
                ),
                'Vippay' => array(
                    'foreignKey' => false,
                    'conditions' => array_merge(
                        array('WaitingPayment.order_id = Vippay.order_id')
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

        $this->paginate = array(
            'WaitingPayment' => array(
                'fields' => array('WaitingPayment.*','Payment.*', 'User.username', 'User.id', 'Game.title', 'Game.os', 'Vippay.type'),
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User', 'Payment', 'Vippay'
                ),
                'order' => array('WaitingPayment.id' => 'DESC'),
                'recursive' => -1,
                'limit' => 20
            )
        );

        $orders = $this->paginate();

        $status = array(
            WaitingPayment::STATUS_WAIT         => 'create',
            WaitingPayment::STATUS_QUEUEING     => 'wait',
            WaitingPayment::STATUS_COMPLETED    => 'success',
            WaitingPayment::STATUS_ERROR        => 'error',
        );

        $this->loadModel('Payment');
        $chanels = array(
            Payment::CHANEL_VIPPAY      => 'Vippay',
            Payment::CHANEL_HANOIPAY    => 'Hanoipay',
            Payment::CHANEL_PAYPAL      => 'Paypal',
            Payment::CHANEL_MOLIN       => 'Molin',
            Payment::CHANEL_ONEPAY      => 'Onepay',
        );

        $this->set(compact('orders', 'games', 'status', 'chanels'));
    }

    public function api_index(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
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

        $limit = 5;
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

        $data_tmp = array();
        foreach ($data as $item){
            $item_status = "";
            if( isset($item['WaitingPayment']['status']) ){
                switch ( $item['WaitingPayment']['status'] ){
                    case WaitingPayment::STATUS_WAIT :
                        $item_status = "Tạo giao dịch";
                        break;
                    case WaitingPayment::STATUS_QUEUEING :
                        $item_status = "Chờ giao dịch";
                        break;
                    case WaitingPayment::STATUS_COMPLETED :
                        $item_status = "Thành công";
                        break;
                    case WaitingPayment::STATUS_ERROR :
                        $item_status = "Thẻ lỗi";
                        break;
                }
            }

            $item_price = 0;
            if( !empty($item['Payment']['price']) ) $item_price = $item['Payment']['price'];

            $item_type = '';
            if( !empty($item['Payment']['type']) ){
                $item_type = $this->Payment->convertType( $item['Payment']['type'] );
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
        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }
}
