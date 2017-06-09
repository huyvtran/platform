<?php

App::uses('CakeTime', 'Utility');
App::uses('AppController', 'Controller');

class ArpuController extends AppController {

	public $components = array('Search.Prg');

	public $uses = array('LogArpuByDay');

	public $useModel = 'LogArpuByDay';
	
	public $presetVars = true;

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

	public function index()
	{
		$this->Prg->commonProcess();
        $parsedConditions = $this->LogArpuByDay->parseCriteria($this->passedArgs);

        list($fromTime, $toTime) = $this->__processDates();
        $rangeDates = $this->LogArpuByDay->getDates($fromTime, $toTime);

        $games = $this->LogArpuByDay->Game->find('list', array(
            'conditions' => array(
                'Game.id' => $this->Auth->user('permission_game_stats'),
                'Game.status' => 1
            )
        ));

		$gamesCond = array('LogArpuByDay.game_id' => $this->Auth->user('permission_game_stats'));

        if (empty($this->request->params['fromTime'])) {
            $timeCond = (array) CakeTime::daysAsSql($fromTime, $toTime, $this->useModel . '.day');
        }

        $parsedConditions = array_merge($gamesCond, (array) $parsedConditions, $timeCond);

		$log = $this->LogArpuByDay->find('all', array(
			'conditions' => $parsedConditions,
			'recursive' => -1,
			'order' => array('game_id' => 'DESC')
		));
		
        $data = $this->LogArpuByDay->dataToChartLine($log, $games, $fromTime, $toTime);
        $data = Hash::sort($data, '{n}.name', 'asc');
        $data2 = $this->LogArpuByDay->addLineTotal($data);

		if (empty($data)) {
			$this->Session->setFlash('No avaiable data in this time range.', 'warning');
		}

		$this->set(compact('games', 'fromTime', 'toTime', 'data', 'data2', 'rangeDates'));
	}
}