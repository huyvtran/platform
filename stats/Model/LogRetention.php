<?php

App::uses('AppModel', 'Model');

class LogRetention extends AppModel {

	public $useTable = 'log_retention_by_day';
	public $belongsTo = array('Game');

    public function retentionToChart($data, $games, $from = null, $to = null, $returnDate)
	{
		$return = array();

		foreach($data as $v) {
			$v = $v[$this->alias];
			# if this games is not exist.
			if (!isset($games[$v['game_id']])) {
				continue;
			}
			if (!isset($return[$v['game_id']])) {
				$return[$v['game_id']]['name'] = $games[$v['game_id']];
				$return[$v['game_id']]['game_id'] = $v['game_id'];
				$return[$v['game_id']]['return'][$v['day']]['return'] = $v['return' . $returnDate];
				$return[$v['game_id']]['register'][$v['day']]['register'] = $v['reg' . $returnDate];
				$return[$v['game_id']]['data'][$v['day']] = (int) $v['value'];
			} else {
				$return[$v['game_id']]['data'][$v['day']] = (int) $v['value'];
			}
		}
		if (isset($from, $to)) {
			$rangeDates = $this->getDates($from, $to);
			foreach($return as &$v) {
				foreach($rangeDates as $day) {
					if (!isset($v['data'][$day])) {
						$v['data'][$day] = 0;
					}
				}
				ksort($v['data']);
				$v['data'] = array_values($v['data']);
			}
		}
		$return = array_values($return);
		return $return;
	}
}