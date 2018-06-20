<?php

App::uses('AppModel', 'Model');

class WaitingPayment extends AppModel {

	public $useTable = 'waiting_payments';

	const STATUS_WAIT  		= 0; // khi tạo giao dịch nạp
	const STATUS_QUEUEING  	= 1; // chờ cổng game trả về, có thể bị timeout
	const STATUS_COMPLETED  = 2; // xác nhận thành công
	const STATUS_ERROR  	= 3; // cổng game trả về, thẻ lỗi hoặc đã sử dụng
    const STATUS_REVIEW  	= 4; // cổng game trả về, thẻ đã bị trừ tiền nhưng có khả năng hoàn trả
    const STATUS_REFUN  	= 5; // user refun

    public $actsAs = array(
        'Search.Searchable'
    );

    public $filterArgs = array(
        'order_id'  => array('type' => 'value'),
        'game_id'   => array('type' => 'value'),
        'status'    => array('type' => 'value'),
        'chanel'    => array('type' => 'value'),
        'type'      => array('type' => 'value'),
        'username'  => array('type' => 'like', 'field' => array('User.id', 'User.username', 'User.email')),
        'cardnumber'=> array('type' => 'value', 'field' => 'card_serial'),
        'cardcode'  => array('type' => 'value', 'field' => 'card_code'),
        'from_time' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'WaitingPayment.time >= '),
        'to_time'   => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'WaitingPayment.time <= '),

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
            $this->bindModel(array(
                'belongsTo' => array('User')
            ));
            $extra['contain'] = array_merge($extra['contain'], array('User'));
        }

        return $this->find('count', array_merge($parameters, $extra));
    }
}
