<?php

App::uses('CakeTime', 'Utility');
App::uses('AppController', 'Controller');

class PagesController extends AppController {

	public $components = array('Search.Prg' => array('model' => 'LogLoginsByDay'));

	public $name = 'Pages';

	public $uses = array('LogLoginsByDay','LogAccountsByDay');
	
	public $presetVars = true;

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow();
		if (isset($this->request->params['pass'][0]) && method_exists($this, $this->request->params['pass'][0])) {
			$this->setAction($this->request->params['pass'][0]);
		}		
	}

	public function home()
	{
		$dauIndexPermission = $this->Acl->check($this->Auth->user(), '/daus/index');
		$niuIndexPermission = $this->Acl->check($this->Auth->user(), '/nius/index');
		
		if (!$this->Auth->loggedIn()) {
			$this->render();
			$this->response->send();
			$this->_stop();
		}
		ini_set('memory_limit', '256M');

		if (!empty($this->request->data)) {
			$this->Prg->commonProcess();
		}

		list($fromTime, $toTime) = $this->__processDates();
		$parsedConditions = $this->LogLoginsByDay->parseCriteria($this->passedArgs);
		$games = $this->LogLoginsByDay->Game->find('list', array(
			'conditions' => array(
				'Game.status' => 1,
				'Game.id' => $this->Auth->user('permission_game_stats')
			)
		));

		if (empty($parsedConditions)) {
			$parsedConditionsNiu = CakeTime::daysAsSql($fromTime, $toTime, 'LogLoginsByDay.day');
			$parsedConditionsDau = CakeTime::daysAsSql($fromTime, $toTime, 'LogAccountsByDay.day');
		} else  {
			foreach ($parsedConditions as $key => $value) {
				list($m, $f) = explode('.', $key);

				# replace key value time for revenue
				if (strpos($f, 'day') !== false ) {
					$parsedConditionsNiu['LogLoginsByDay.' . $f] = $value;
					$parsedConditionsDau['LogAccountsByDay.' . $f] = $value;
				# replace key value game_id for dau, niu
				} else if (strpos($f, 'game_id') !== false ) {
                    if (!is_array($value)) {
                        $g = $this->LogLoginsByDay->Game->findByApp($value);
                        $game = $g;
                        $f = 'game_id';
                        $value = $g['Game']['id'];
                    } else {
                        $g = $this->LogLoginsByDay->Game->findAllByApp($value);
                        $game = $g;
                        $f = 'game_id';
                        $value = Hash::extract($g, '{n}.Game.id');
                    }
                    $parsedConditionsNiu['LogLoginsByDay.' . $f] = $value;
                    $parsedConditionsDau['LogAccountsByDay.' . $f] = $value;

				} else {
					$parsedConditionsNiu['LogLoginsByDay.' . $f] = $value;
					$parsedConditionsDau['LogAccountsByDay.' . $f] = $value;
				}
			}
		}

		$daus = $this->LogLoginsByDay->find('all', array(
			'conditions' => $parsedConditionsNiu,
			'recursive' => -1,
			'order' => array('game_id' => 'DESC')
		));

		$nius = $this->LogAccountsByDay->find('all', array(
				'conditions' => $parsedConditionsDau,
				'recursive' => -1,
				'order' => array('game_id' => 'DESC')
		));

		$nius = $this->LogAccountsByDay->dataToChartLine($nius, $games,
			$fromTime, $toTime);
		$nius =  Hash::sort($nius, '{n}.name', 'asc');
		$nius = $this->LogAccountsByDay->addLineTotal($nius);

		$daus = $this->LogLoginsByDay->dataToChartLine($daus, $games, $fromTime, $toTime);
		$daus =  Hash::sort($daus, '{n}.name', 'asc');
		$daus = $this->LogLoginsByDay->addLineTotal($daus);

		$rangeDates = $this->LogLoginsByDay->getDates($fromTime, $toTime);

		$this->set(compact(
			'games', 'fromTime', 'toTime', 'game', 'dauIndexPermission', 'niuIndexPermission',
			'nius', 'daus', 'rangeDates'
		));
		$this->render('home');
	}
}
