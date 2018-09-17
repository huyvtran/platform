<?php

App::uses('AppModel', 'Model');

class Transaction extends AppModel {

	public $useTable = 'transactions';

	const TYPE_PAY    	= 1; // nạp tiền vào tài khoản
	const TYPE_SPEND 	= 2; // rút tiên từ tài khoản chuyển vào game

	public $belongsTo = array(
		'User', 'Game'
	);

    public $actsAs = array(
        'Search.Searchable'
    );

    public $filterArgs = array(
        'order_id' => array('type' => 'value'),
        'game_id' => array('type' => 'value'),
        'type' => array('type' => 'value'),
        'username' => array('type' => 'like', 'field' => array('User.id', 'User.username', 'User.email')),
        'from_time' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'Transaction.created >= '),
        'to_time' => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'Transaction.created <= '),

    );

    public function fromTimeCond($data = array())
    {
        return date('Y-m-d 0:0:0', $data['from_time']);
    }

    public function toTimeCond($data = array())
    {
        return date('Y-m-d 23:59:59', $data['to_time']);
    }

    function paginateCount($conditions = array(), $recursive = 0, $extra = array())
    {
        $parameters = compact('conditions');
        if ($recursive != $this->recursive) {
            $parameters['recursive'] = $recursive;
        }

        $extra['recursive'] = -1;
        $extra['contain'] = array();
        if(isset($conditions['OR']['User.username LIKE'])    ||
            isset($conditions['OR']['User.email LIKE'])
        ){
            $extra['contain'] = array_merge($extra['contain'], array('User'));
        }

        return $this->find('count', array_merge($parameters, $extra));
    }
}
