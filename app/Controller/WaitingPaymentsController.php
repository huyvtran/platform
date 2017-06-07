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
                'fields' => array('WaitingPayment.*','Payment.*', 'User.username', 'User.id', 'Game.title', 'Game.os'),
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User', 'Payment'
                ),
                'order' => array('Payment.id' => 'DESC'),
                'recursive' => -1,
                'limit' => 20
            )
        );

        $orders = $this->paginate();

        $this->set(compact('orders', 'games'));
    }
}
