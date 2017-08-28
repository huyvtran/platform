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
//		    $addGroupBy = array('type');
	    }

        # END PERMISSIONS
        $fields_revenues = array('SUM(0.8*price) as sum', 'game_id', 'FROM_UNIXTIME(time, "%Y-%m-%d") as day', 'type');
        $fields_total = array('SUM(0.8*price) as sum', 'game_id', 'type');
	    if( in_array($this->Auth->user('username'), array('quanvh', 'admin')) ){
            $fields_revenues = array('SUM(price_end) as sum', 'game_id', 'FROM_UNIXTIME(time, "%Y-%m-%d") as day', 'type');
            $fields_total = array('SUM(price_end) as sum', 'game_id', 'type');
        }

        $revenues = $this->Payment->find('all', array(
            'fields' => $fields_revenues,
            'conditions' => array_merge($parsedConditions, array(
                'Payment.time >= ' => $fromTime,
                'Payment.time <= ' => $toTime,
                'Payment.test' => 0
            )),
            'group' => array_merge(array('game_id', 'day'), $addGroupBy),
            'recursive' => -1,
            'order' => array('game_id' => 'DESC')
        ));
        $revenues = array_values($revenues);
//        debug($this->Payment->getLastQuery());
//        debug($revenues);die;

        $payTypes = array(
            'VTT' => 'VTT',
            'VMS' => 'VMS',
            'VNP' => 'VNP'
        );

        # tính cho lượt trước để so sánh tỉ lệ tăng hay giảm với hiện tại
		$total = $this->Payment->find('all', array(
			'fields' => $fields_total,
			'conditions' => array_merge($parsedConditions, array(
				'Payment.time >= ' => $start,
				'Payment.time < ' => $end,
                    'Payment.test' => 0
			)),
			'group' => array_merge(array('game_id'), $addGroupBy),
			'recursive' => -1,
			'order' => array('game_id' => 'DESC')
		));

        if (empty($this->request->params['named']['game_id'])) {
            $gameId = array_keys($games);
        } else {
            $gameId = $parsedConditions['Payment.game_id'];
        }

        if (!empty($this->request->params['named']['game_id']) && !$this->request->is('ajax')) {
            # If select a game, then show all type payments
            $data = $this->Payment->dataToChartLine($revenues, $games, $fromTime, $toTime);
        } else if (!$this->request->is('ajax')) {
            if (Cache::read('total_alltime', 'default') == false) {
                $gameTotals = $this->Payment->getTotals($gameId);
                Cache::write('total_alltime', $gameTotals, 'default');
            } else {
                $gameTotals = Cache::read('total_alltime', 'default');
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

    public function country() {
        $this->modelClass = 'LogPaymentsCountryByDay';
        $this->indexCountry();
    }
}
