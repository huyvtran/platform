<?php

App::uses('AppModel', 'Model');

class BankManual extends AppModel {

	public $useTable = 'bank_manuals';

    public $actsAs = array(
        'Search.Searchable'
    );

    public $validate = array(
        'buyer_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter full name'
            )
        ),
		'buyer_phone' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter phone'
            )
        ),
        'buyer_email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter email'
            )
        ),
        'price' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please chose a price'
            )
        ),
    );

    public $filterArgs = array(
        'order_id'  => array('type' => 'value'),
        'game_id'   => array('type' => 'value'),
        'status'        => array('type' => 'value'),
        'buyer_name'    => array('type' => 'like'),
        'buyer_phone'   => array('type' => 'value'),
        'buyer_email'   => array('type' => 'value'),
        'username'  => array('type' => 'like', 'field' => array('User.id', 'User.username', 'User.email')),
        'from_time' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'BankManual.modified >= '),
        'to_time'   => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'BankManual.modified <= '),

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
            $this->bindModel(array(
                'belongsTo' => array('User')
            ));
            $extra['contain'] = array_merge($extra['contain'], array('User'));
        }

        return $this->find('count', array_merge($parameters, $extra));
    }
}
