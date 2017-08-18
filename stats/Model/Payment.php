<?php

App::uses('AppModel', 'Model');

class Payment extends AppModel {

	public $belongsTo = array(
		'Game', 'User'
	);
	
	public $actsAs = array(
		'Search.Searchable'
	);
	
	public $filterArgs = array(
		'game_id' => array('type' => 'value'),
		'type' => array('type' => 'value'),
		'fromTime' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'Payment.time >= '),
		'toTime' => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'Payment.time <= ')
	);

    // show query
    function getLastQuery()
    {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }

    public function getTotals($gameIds = array())
    {
        $sums = $this->find('all', array(
            'fields' => array('SUM(0.8*price) as sum', 'game_id'),
            'conditions' => array(
                'game_id' => $gameIds,
                'test' => 0
            ),
            'group' => array('game_id'),
            'recursive' => -1
        ));

        if (!empty($sums)) {
            foreach ($sums as $sum) {
                $tmp[$sum['Payment']['game_id']] = $sum[0]['sum'];
            }

            return $tmp;
        }
        return array();
    }

    public function dataToChartLine($data, $games, $from = null, $to = null)
    {
        $return = array();
        $gameIds = $this->Game->find('list', array(
            'fields' => array('id', 'id'),
            'recursive' => -1
        ));

        foreach ($data as $a) {
            if (!isset($games[$a['Payment']['game_id']])) {
                continue;
            }

            $return[$a['Payment']['game_id']]['game_id'] = $gameIds[$a['Payment']['game_id']];
            $return[$a['Payment']['game_id']]['data'][$a[0]['day']] = (int) $a[0]['sum'];
            $return[$a['Payment']['game_id']]['name'] = $games[$a['Payment']['game_id']];
        }

        $range = $this->getDates($from, $to);
        $return = $this->__fillData($return, $range);

        return $return;
    }

    private function __fillData($return, $range)
    {
        if (!empty($return)) {
            foreach ($return as &$v) {
                foreach ($range as $time) {
                    if (!isset($v['data'][$time])) {
                        $v['data'][$time] = 0;
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
