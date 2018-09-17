<?php
App::uses('AppController', 'Controller');

class TransactionsController extends AppController {

    public $components = array('Paginator', 'Search.Prg');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }
    
    public function admin_index()
    {
        $this->layout = 'default_bootstrap';

        $this->Prg->commonProcess();
        $this->request->data['Transaction'] = $this->passedArgs;
        
        $parsedConditions = array();
        if(!empty($this->passedArgs)) {
            $parsedConditions = $this->Transaction->parseCriteria($this->passedArgs);
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
            'Transaction.game_id' => $this->Session->read('Auth.User.permission_game_default')
        ), $parsedConditions);

        $games = $this->Transaction->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array(
                'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
            )
        ));

        $this->paginate = array(
            'Transaction' => array(
                'fields' => array('Transaction.*', 'User.username', 'User.id', 'Game.title', 'Game.os'),
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User'
                ),
                'recursive' => -1,
                'limit' => 20
            )
        );

        $orders = $this->paginate();

        $this->set(compact('orders', 'games'));
    }
}
