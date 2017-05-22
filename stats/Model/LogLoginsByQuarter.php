<?php

App::uses('AppModel', 'Model');

/**
 * Lưu record chứa thông tin thiết bị của users tại thời điểm login
 * có thể sử dụng để thống kê thông tin thiết bị sử dụng.
 */
class LogLoginsByQuarter extends AppModel {

	public $useTable = 'log_logins_by_day';

	public $belongsTo = array('Game');

	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
		'game_id' => array('type' => 'value'),
		'Y' => array('type' => 'value')
	);

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
		'Y' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		)				
	);

}