<?php

App::uses('AppModel', 'Model');

/**
 * Lưu record chứa thông tin thiết bị của users tại thời điểm login
 * có thể sử dụng để thống kê thông tin thiết bị sử dụng.
 */
class LogLogin extends AppModel {

	public $actsAs = array(
		'Search.Searchable'
	);

	public $belongsTo = array('Game');

	public $filterArgs = array(
		'game_id' => array('type' => 'value'),
		'fromTime' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'LogLogin.created >= '),
		'toTime' =>  array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'LogLogin.created <= '),
	);

	public function fromTimeCond($data = array())
	{
		return date('Y-m-d', $data['fromTime']);
	}

	public function toTimeCond($data = array())
	{
		return date('Y-m-d', $data['toTime']);
	}

	public $validate = array(
		'user_id' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		),
		'os' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		),
		'resolution' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		),						
		'sdk_ver' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		),						
		'game_id' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		),						
		'g_ver' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		),						
		'device' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		)
	);

}