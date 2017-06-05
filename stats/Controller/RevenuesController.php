<?php

App::uses('CakeTime', 'Utility');
App::uses('AppController', 'Controller');

class RevenuesController extends AppController {

    public $components = array('Search.Prg');
    public $uses = array('Payment','User');
    public $presetVars = true;
    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function index()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(5000);

        $this->Prg->commonProcess();
        list($fromTime, $toTime) = $this->__processDates();
        $rangeDates = $this->Payment->getDates($fromTime, $toTime);
        list ($start, $end) = $this->getDate($fromTime, count($rangeDates));

        $parsedConditions = $this->Payment->parseCriteria($this->passedArgs);

        # PERMISSIONS
        $ids = $this->Auth->user('permission_game_stats');
        $games = $this->Payment->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array('Game.id' => $ids, 'Game.status' => 1)
        ));
        $gamesCond = array('Payment.game_id' => array_keys($games));
        $parsedConditions = array_merge($gamesCond, (array) $parsedConditions);

	    $addGroupBy = array();
	    if (!empty($this->request->params['named']['game_id']) && !$this->request->is('ajax')) {
		    $addGroupBy = array('type');
	    }

        # END PERMISSIONS
	    if (!empty($this->request->params['named']['type'])) {
		    $revenues = $this->Payment->find('all', array(
			    'fields' => array('SUM(price) as sum', 'game_id', 'FROM_UNIXTIME(time, "%Y-%m-%d") as day', 'type'),
			    'conditions' => array_merge($parsedConditions, array(
			        'Payment.time >= ' => $fromTime,
                    'Payment.time < ' => $toTime,
                    'Payment.test' => 0
                )),
			    'group' => array_merge(array('game_id', 'day'), $addGroupBy),
			    'recursive' => -1,
			    'order' => array('game_id' => 'DESC')
		    ));

            debug($revenues);die;
	    } else {
		    $revenues_card = $this->Payment->find('all', array(
			    'fields' => array('SUM(price) as sum', 'game_id', 'FROM_UNIXTIME(time, "%Y-%m-%d") as day', 'type'),
			    'conditions' => array_merge($parsedConditions, array(
                    'Payment.time >= ' => $fromTime,
                    'Payment.time <= ' => $toTime,
                    'Payment.test' => 0
                )),
			    'group' => array_merge(array('game_id', 'day'), $addGroupBy),
			    'recursive' => -1,
			    'order' => array('game_id' => 'DESC')
		    ));

            $revenues = $revenues_card;
//            debug($this->Payment->getLastQuery());
//            debug($revenues);die;
            
//		    $revenues_merge = array_merge($revenues_card, array());
//		    $revenues = array();
//		    if (empty($addGroupBy)) {
//			    foreach ($revenues_merge as $key => $value) {
//				    if (!isset($revenues[$value['Payment']['game_id'] . '_' . $value['0']['day']])) {
//					    $revenues[$value['Payment']['game_id'] . '_' . $value['0']['day']]['0']['sum'] = $value['0']['sum'];
//				    } else {
//					    $revenues[$value['Payment']['game_id'] . '_' . $value['0']['day']]['0']['sum'] += $value['0']['sum'];
//				    }
//				    $revenues[$value['Payment']['game_id'] . '_' . $value['0']['day']]['Payment']['type'] = $value['Payment']['type'];
//				    $revenues[$value['Payment']['game_id'] . '_' . $value['0']['day']]['Payment']['game_id'] = $value['Payment']['game_id'];
//				    $revenues[$value['Payment']['game_id'] . '_' . $value['0']['day']]['0']['day'] = $value['0']['day'];
//			    }
//		    } else {
//			    foreach ($revenues_merge as $key => $value) {
//				    if (!isset($revenues[$value['Moborder']['app_key'] . '_' . $value['0']['day'] . '_' . $value['Moborder']['type']])) {
//					    $revenues[$value['Moborder']['app_key'] . '_' . $value['0']['day'] . '_' . $value['Moborder']['type']]['0']['sum'] = $value['0']['sum'];
//				    } else {
//					    $revenues[$value['Moborder']['app_key'] . '_' . $value['0']['day'] . '_' . $value['Moborder']['type']]['0']['sum'] += $value['0']['sum'];
//				    }
//				    $revenues[$value['Moborder']['app_key'] . '_' . $value['0']['day'] . '_' . $value['Moborder']['type']]['Moborder']['type'] = $value['Moborder']['type'];
//				    $revenues[$value['Moborder']['app_key'] . '_' . $value['0']['day'] . '_' . $value['Moborder']['type']]['Moborder']['app_key'] = $value['Moborder']['app_key'];
//				    $revenues[$value['Moborder']['app_key'] . '_' . $value['0']['day'] . '_' . $value['Moborder']['type']]['0']['day'] = $value['0']['day'];
//			    }
//		    }
		    $revenues = array_values($revenues);
	    }

        $payTypes = array(
            'VTT' => 'VTT',
            'VMS' => 'VMS',
            'VNP' => 'VNP'
        );

        /*
        $payTypes = $this->Payment->find('all', array(
            'fields' => array('DISTINCT type'),
            'conditions' => array(
                'Payment.time >= ' => $fromTime,
                'Payment.time < ' => $toTime,
                'Payment.test' => 0
            ),
            'recursive' => -1,
        ));

        if (!empty($payTypes)) {
            $payTypes = Hash::combine($payTypes, "{n}.Payment.type", "{n}.Payment.type");
        }
        */

        # tính cho lượt trước để so sánh tỉ lệ tăng hay giảm với hiện tại
	    if (!empty($this->request->params['named']['type'])) {
		    $total = $this->Payment->find('all', array(
			    'fields' => array('SUM(price) as sum', 'game_id', 'type'),
			    'conditions' => array_merge($parsedConditions, array(
			        'Payment.time >= ' => $start,
                    'Payment.time < ' => $end,
                    'Payment.test' => 0
                )),
			    'group' => array_merge(array('game_id'), $addGroupBy),
			    'recursive' => -1,
			    'order' => array('game_id' => 'DESC')
		    ));
	    } else {
		    $old_revenues_card = $this->Payment->find('all', array(
			    'fields' => array('SUM(price) as sum', 'game_id', 'type'),
			    'conditions' => array_merge($parsedConditions, array(
                    'Payment.time >= ' => $start,
                    'Payment.time < ' => $end,
                    'Payment.test' => 0
                )),
			    'group' => array_merge(array('game_id'), $addGroupBy),
			    'recursive' => -1,
			    'order' => array('game_id' => 'DESC')
		    ));

		    $total = array();
		    $old_revenues_merge = array_merge($old_revenues_card, array());
		    if (empty($this->request->params['named']['game_id'])) {
			    foreach ($old_revenues_merge as $key => $value) {
				    if (!isset($total[$value['Payment']['game_id']])) {
					    $total[$value['Payment']['game_id']]['sum'] = $value['0']['sum'];
				    } else {
					    $total[$value['Payment']['game_id']]['sum'] += $value['0']['sum'];
				    }
				    $total[$value['Payment']['game_id']]['game_id'] = $value['Payment']['game_id'];
			    }
		    } else {
			    foreach ($old_revenues_merge as $key => $value) {
				    if (!isset($total[$value['Payment']['game_id']])) {
					    $total[$value['Payment']['game_id']]['sum'] = $value['0']['sum'];
				    } else {
					    $total[$value['Payment']['game_id']]['sum'] += $value['0']['sum'];
				    }
				    $total[$value['Payment']['game_id']]['type'] = $value['Payment']['type'];
			    }
		    }
		    $total = array_values($total);
	    }

        if (empty($this->request->params['named']['id'])) {
            $gameId = array_keys($games);
        } else {
            $gameId = $parsedConditions['Payment.game_id'];
        }
        if (!empty($this->request->params['named']['game_id']) && !$this->request->is('ajax')) {
            # If select a game, then show all type payments
            $data = $this->Payment->dataToChartLine2($revenues, $games, $fromTime, $toTime);
        } else if (!$this->request->is('ajax')) {
            if (Cache::read('total_alltime', '3_day') == false) {
                $gameTotals = $this->Payment->getTotals($gameId);
                Cache::write('total_alltime', $gameTotals, '3_day');
            } else {
                $gameTotals = Cache::read('total_alltime', '3_day');
            }
            foreach ($gameTotals as &$v) {
                $v = ($v / 100) * 20000;
            }

            $data = $this->Payment->dataToChartLine($revenues, $games, $fromTime, $toTime);
        } else {
            $data = $this->Payment->dataToChartLine($revenues, $games, $fromTime, $toTime);
        }
        if (empty($data)) {
            $this->Session->setFlash('No avaiable data in this time range.', 'warning');
        }

        $data = Hash::sort($data, '{n}.name', 'asc');
        $data2 = $this->Payment->addLineTotal($data);
        
        $idToName = $this->Payment->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array(
                'Game.id' => $this->Auth->user('permission_game_stats'),
                'Game.status' => 1
            )
        ));
        $this->set(compact('games', 'fromTime', 'toTime', 'revenues', 'data', 'rangeDates', 'gameTotals', 'payTypes', 'idToName', 'data2', 'total'));
    }

//    public function country() {
//        $this->modelClass = 'LogMobordersCountryByDay';
//        $this->indexCountry();
//    }
//
//    public function quarter()
//    {
//        ini_set('memory_limit', '2048M');
//        set_time_limit(10000);
//        $this->Prg->commonProcess();
//
//        list($fromTime, $toTime) = $this->__processQuarter();
//
//        $parsedConditions = $this->Moborder->parseCriteria($this->passedArgs);
//        $addGroupBy = array();
//        if (!empty($this->request->params['named']['app_key'])) {
//            $addGroupBy = array('type');
//        }
//
//        $games = $this->Moborder->Game->find('list', array(
//            'fields' => array('app_key', 'title_os'),
//            'conditions' => array(
//                'Game.id' => $this->Auth->user('permission_game_stats'),
//                'Game.status' => 1
//            )
//        ));
//        $gamesCond = array('Moborder.app_key' => array_keys($games));
//
//        $parsedConditions = array_merge($gamesCond, (array) $parsedConditions);
//
//        $revenues = $this->Moborder->find('all', array(
//            'fields' => array('SUM(platform_price) as sum', 'app_key', 'FROM_UNIXTIME(time, "%Y-%m-%d") as day', 'type'),
//            'conditions' => array_merge($parsedConditions, array('Moborder.time >= ' => $fromTime, 'Moborder.time < ' => $toTime, 'Moborder.test_type' => 0)),
//            'group' => array_merge(array('app_key', 'day'), $addGroupBy),
//            'recursive' => -1,
//            'order' => array('app_key' => 'DESC')
//        ));
//
//        $payTypes = $this->Moborder->find('all', array(
//            'fields' => array('DISTINCT type'),
//            'conditions' => array('Moborder.time >= ' => $fromTime, 'Moborder.time < ' => $toTime, 'Moborder.test_type' => 0),
//            'recursive' => -1,
//        ));
//        if (!empty($payTypes)) {
//            $payTypes = Hash::combine($payTypes, "{n}.Moborder.type", "{n}.Moborder.type");
//        }
//        if (!empty($this->request->params['named']['app_key'])) {
//            # If select a game, then show all type payments
//            $data = $this->Moborder->dataToChartLineQuarter2($revenues, $games, $fromTime, $toTime);
//        } else {
//            $data = $this->Moborder->dataToChartLineQuarter($revenues, $games, $fromTime, $toTime);
//        }
//        if (empty($data)) {
//            $this->Session->setFlash('No avaiable data in this time range.', 'warning');
//        }
//
//        if (empty($this->request->params['named']['app_key'])) {
//            $appKey = array_keys($games);
//            if (Cache::read('total_alltime', '3_day') == false) {
//                $gameTotals = $this->Moborder->getTotals($appKey);
//                Cache::write('total_alltime', $gameTotals, '3_day');
//            } else {
//                $gameTotals = Cache::read('total_alltime', '3_day');
//            }
//            foreach ($gameTotals as &$v) {
//                $v = $v / 100;
//            }
//        }
//
//        $data = $this->Moborder->convertToUSD($data);
//        $data = Hash::sort($data, '{n}.name', 'asc');
//
//        $idToName = $this->Moborder->Game->find('list', array(
//            'fields' => array('id', 'title_os'),
//            'conditions' => array(
//                'Game.id' => $this->Auth->user('permission_game_stats'),
//                'Game.status' => 1
//            )
//        ));
//        $rangeDates = $this->Moborder->getDates($fromTime, $toTime, 'd-m-Y', new DateInterval('P3M'));
//        $this->set(compact('games', 'fromTime', 'toTime', 'revenues', 'data', 'rangeDates', 'gameTotals', 'payTypes', 'idToName'));
//    }

}
