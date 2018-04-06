<?php

App::uses('AppModel', 'Model');

class Charge extends AppModel {

	public $useTable = 'charges';

	const STATUS_WAIT  		= 0; // khi tạo giao dịch nạp
    const STATUS_COMPLETED  = 1; // xác nhận thành công
    const STATUS_ERROR  	= 2; // cổng game trả về, thẻ lỗi hoặc đã sử dụng
	
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
