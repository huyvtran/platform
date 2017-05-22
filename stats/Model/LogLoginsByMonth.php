<?php

App::uses('AppModel', 'Model');

/**
 * Lưu record chứa thông tin thiết bị của users tại thời điểm login
 * có thể sử dụng để thống kê thông tin thiết bị sử dụng.
 */
class LogLoginsByMonth extends AppModel {

	public $useTable = 'log_logins_by_month';

	public $belongsTo = array('Game');

	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
		'game_id' => array('type' => 'value'),
		'fromTime' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'time >= '),
		'toTime' =>  array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'time <= '),
	);

	public function fromTimeCond($data = array())
	{
		list($month, $year) = explode('-', $data['fromTime']);
		return $year . "-" . $month . "-00";
	}

	public function toTimeCond($data = array())
	{
		list($month, $year) = explode('-', $data['toTime']);
		return $year . "-" . $month . "-31";
	}

	public $validate = array(
		'value' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		),
		'game_id' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		),						
		'time' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		)				
	);

}