<?php

App::uses('AppModel', 'Model');

class LogAccountsByDay extends AppModel {

	public $useTable = 'log_accounts_by_day';

	public $actsAs = array('Search.Searchable');

	public $belongsTo = array('Game');

	public $validate = array(
		'value' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'allowEmpty' => false
		),
		'game_id' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'allowEmpty' => false
		),
		'day' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'allowEmpty' => false
		)
	);

}