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

        $this->set(compact('orders', 'games'));
    }

    public function api_index(){
        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui lòng login', 'payment');
            throw new NotFoundException('Vui lòng login');
        }
        $user = $this->Auth->user();

        $this->loadModel('WaitingPayment');
        $this->loadModel('Game');
        $gameIds = $this->Game->getSimilarGameId($game);

        $limit = 5;
        $page = 1;
        if ( !empty($this->request->query('page')) ){
            $page = $this->request->query('page');
        }
        $offset = ($page - 1)* $limit;

        $data = $this->WaitingPayment->find('list', array(
            'fields'     => array('WaitingPayment.*'),
            'conditions' => array(
                'user_id'   => $user['id'],
                'game_id'   => $gameIds
            ),
            'recursive' => -1,
            'limit'     => $limit,
            'offset'    => $offset,
            'order'     => array('id desc')
        ));

        debug($data);die;
    }
}
