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

        'from_time' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'MobOrder.time >= '),
        'to_time' => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'MobOrder.time <= '),

    );

    function paginateCount($conditions = array(), $recursive = 0, $extra = array())
    {
        $parameters = compact('conditions');
        if ($recursive != $this->recursive) {
            $parameters['recursive'] = $recursive;
        }

        $extra['recursive'] = -1;
        $extra['contain'] = array();
//        if(isset($conditions['WaitingOrder.confirm_status'])){
//            $extra['contain'] = array_merge( $extra['contain'], array('WaitingOrder') );
//        }
//        if(isset($conditions['PaydirectOrder.cardnumber']) || isset($conditions['PaydirectOrder.cardserial'])){
//            $extra['contain'] = array_merge( $extra['contain'], array('PaydirectOrder') );
//        }
        if(isset($conditions['OR']['User.username LIKE'])    ||
            isset($conditions['OR']['User.email LIKE'])      ||
            isset($conditions['OR']['User.name LIKE'])       ||
            isset($conditions['OR']['User.slug LIKE'])
        ){
            $extra['contain'] = array_merge($extra['contain'], array('User'));
        }

        return $this->find('count', array_merge($parameters, $extra));
    }
}
