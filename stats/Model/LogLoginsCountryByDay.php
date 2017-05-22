<?php

App::uses('AppModel', 'Model');

class LogLoginsCountryByDay extends AppModel {

	public $useTable = 'log_logins_country_by_day';

	public $belongsTo = array('Game');

	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
		'game_id' => array('type' => 'value'),
		'fromTime' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'LogLoginsCountryByDay.day >= '),
		'toTime' =>  array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'LogLoginsCountryByDay.day <= '),
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
		'country' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'allowEmpty' => false
		),
		'day' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		)				
	);

}