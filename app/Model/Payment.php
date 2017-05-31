<?php

App::uses('AppModel', 'Model');

class Payment extends AppModel {

	public $useTable = 'payments';

	const TYPE_NETWORK_VIETTEL          = 'VTT';
	const TYPE_NETWORK_VINAPHONE        = 'VNP';
	const TYPE_NETWORK_MOBIFONE         = 'VMS';

	const CHANEL_VIPPAY	= 1;

	public $belongsTo = array(
		'User', 'Game'
	);

    public $actsAs = array(
        'Search.Searchable'
    );

    public $filterArgs = array(
        'order_id' => array('type' => 'value'),
        'game_id' => array('type' => 'value'),
        'username' => array('type' => 'like', 'field' => array('User.id', 'User.username', 'User.email')),
        'type' => array('type' => 'value'),
        'chanel' => array('type' => 'value'),
        'cardnumber' => array('type' => 'value', 'field' => 'card_serial'),
        'cardcode' => array('type' => 'value', 'field' => 'card_code'),

        'from_time' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'time >= '),
        'to_time' => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'time <= '),

    );

    public function fromTimeCond($data = array())
    {
        return date('U', strtotime(date('d-m-Y 0:0:0', $data['from_time'])));
    }

    public function toTimeCond($data = array())
    {
        return date('U', strtotime(date('d-m-Y 23:59:59', $data['to_time'])));
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
