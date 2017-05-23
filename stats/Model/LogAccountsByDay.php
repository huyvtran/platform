<?php

/*
 * Store account logs by day.
 */

class LogAccountsByDay extends AppModel {

	public $useTable = 'log_accounts_by_day';

	public $actsAs = array('Search.Searchable');

	public $belongsTo = array('Game');

	public $filterArgs = array(
		'game_id' => array('type' => 'value'),
		'fromTime' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'LogAccountsByDay.day >= '),
		'toTime' => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'LogAccountsByDay.day <= '),
		'fromTime1' => array('type' => 'expression', 'method' => 'fromTimeCond1', 'field' => 'time >= '),
		'toTime1' =>  array('type' => 'expression', 'method' => 'toTimeCond1', 'field' => 'time <= '),
	);

	public function fromTimeCond($data = array())
	{
		return date('Y-m-d', $data['fromTime']);
	}

	public function toTimeCond($data = array())
	{
		return date('Y-m-d', $data['toTime']);
	}

	public function fromTimeCond1($data = array())
	{
		list($month, $year) = explode('-', $data['fromTime1']);
		return $year . "-" . $month . "-00";
	}

	public function toTimeCond1($data = array())
	{
		list($month, $year) = explode('-', $data['toTime1']);
		return $year . "-" . $month . "-31";
	}


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

	public function getTotals($games)
	{
		$sums = $this->find('all', array(
			'fields' => array('SUM(value) as sum', 'game_id'),
			'conditions' => array('game_id' => array_keys($games)),
			'group' => array('game_id'),
			'recursive' => -1
		));

		if (!empty($sums)) {
			foreach($sums as $sum) {
				$tmp[$sum['LogAccountsByDay']['game_id']] = $sum[0]['sum'];
			}
			return $tmp;
		}
		return array();
	}
}