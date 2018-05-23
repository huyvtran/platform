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
            Payment::CHANEL_NL_ALE      => 'Ale/NL',
        );

        if($this->Auth->User('username') == 'cskh1pay'){
            $chanels = array(
                Payment::CHANEL_ONEPAY      => '1Pay',
                Payment::CHANEL_ONEPAY_2    => '1Pay 2',
            );
        }

        $types = array(
            Payment::TYPE_NETWORK_VIETTEL       => 'Viettel',
            Payment::TYPE_NETWORK_VINAPHONE     => 'Vinaphone',
            Payment::TYPE_NETWORK_MOBIFONE      => 'Mobifone',
            Payment::TYPE_NETWORK_GATE          => 'Gate',
            Payment::TYPE_NETWORK_BANKING       => 'Banking',
            Payment::TYPE_NETWORK_CARD          => 'Card',
            Payment::TYPE_NETWORK_SMS           => 'Sms',
        );

        $this->set(compact('orders', 'games', 'status', 'chanels', 'types'));
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
        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }
}
