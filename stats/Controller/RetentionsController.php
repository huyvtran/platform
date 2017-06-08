<?php

App::uses('CakeTime', 'Utility');
App::uses('AppController', 'Controller');

class RetentionsController extends AppController {

	public $components = array('Search.Prg');

	public $uses = array('LogRetention');

	public $useModel = 'LogRetention';

	public $presetVars = true;

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	public function index($retentionDate = null)
	{
		$Model = $this->{$this->useModel};

		$this->Prg->commonProcess();
		list($fromTime, $toTime) = $this->__processDates();
		$parsedConditions = $Model->parseCriteria($this->passedArgs);

		if (!$retentionDate) {
			$retentionDate = 1;
			$this->request->params['pass'][0] = 1;
		}

		$ids = $this->Auth->user('permission_game_stats');
		$games = $Model->Game->find('list', array(
			'conditions' => array('Game.id' => $ids, 'Game.status' => 1)
		));
		
		$gamesCond = array($this->useModel . '.game_id' => $ids);

		if (empty($this->request->params['fromTime'])) {
			$timeCond = (array) CakeTime::daysAsSql($fromTime, $toTime, $this->useModel . '.day');
		}

        $parsedConditions = array_merge($gamesCond, (array) $parsedConditions, $timeCond);

		$logs = $Model->find('all', array(
			'fields' => array("*,
				CASE 
						WHEN reg$retentionDate < 5 THEN 0
						ELSE return$retentionDate / reg$retentionDate * 100
				END as value"),
			'conditions' => $parsedConditions,
			'recursive' => -1,
			'order' => array('game_id' => 'DESC')
		));

		foreach($logs as $k => $log) {
			$logs[$k][$this->useModel]['value'] = $log[0]['value'];
		}

		$data = $Model->retentionToChart($logs, $games, $fromTime, $toTime, $retentionDate);

		if (empty($data)) {
			$this->Session->setFlash('No avaiable data in this time range.', 'warning');
		}
		$rangeDates = $Model->getDates($fromTime, $toTime);
		$this->set(compact('games', 'fromTime', 'toTime', 'data', 'rangeDates', 'retentionDate'));
	}
}