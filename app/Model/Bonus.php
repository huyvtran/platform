<?php

App::uses('AppModel', 'Model');

class Bonus extends AppModel {
	public $useTable = 'bonuses';

    public $actsAs = array(
        'Search.Searchable',
        'Utils.Publishable' => array(
            'field' => 'status'
        ),
    );

	public $validate = array(
		'user_id' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false
		),
        'game_id' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false
        ),
        'price' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty'
            )
        )
	);

    public $belongsTo = array( 'Game' , 'User');

	public $filterArgs = array(
		'game_id'   => array('type' => 'value'),
		'username'  => array('type' => 'like', 'field' => array('User.id', 'User.username', 'User.email')),
        'status'    => array('type' => 'value'),
		//'from_time' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'CompensePayment.modified >= '),
		//'to_time' => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'CompensePayment.modified <= '),
	);

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
