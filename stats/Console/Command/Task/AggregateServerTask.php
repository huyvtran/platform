<?php

class AggregateServerTask extends Shell {
	
	public $uses = array(
		'Moborder', 'LogMobordersServerByDay',
		'LogEntergame', 'LogEntergamesServerByDay',
		'LogAccountsServerByDay',
		'Game', 'Server', 'Account'
	);

	public function Revenue($date)
	{
		$this->out('Running ' . $date);

		$results = $this->Moborder->find('all', array(
			'fields' => array('SUM(platform_price) as sum', 'app_key', 'game_area_id'),
			'conditions' => array(
				'Moborder.time >=' => strtotime($date), 'Moborder.time < ' => strtotime($date) + 86400, 'Moborder.test_type' => 0
			),
			'group' => 'app_key, game_area_id',
			'recursive' => -1
		));

		foreach($results as $result) {

			$game = $this->Game->findByAppKey($result['Moborder']['app_key']);
			
			if (!$game) {
				$this->out('<warning>Can not find this appkey: ' . $result['Moborder']['app_key'] . '</warning>');
				continue;
			}

			$log = $this->LogMobordersServerByDay->find('first', array(
				'conditions' => array(
					'day' => date('Y-m-d', strtotime($date)),
					'game_id' => $game['Game']['id'],
					'area_id' => $result['Moborder']['game_area_id']
				)
			));
			if ($log) {
				$this->LogMobordersServerByDay->id = $log['LogMobordersServerByDay']['id'];
			} else {
				$this->LogMobordersServerByDay->create();
			}

			$data = array(
				'value' => $result[0]['sum'],
				'game_id' => $game['Game']['id'],
				'day' => date('Y-m-d', strtotime($date)),
				'area_id' => $result['Moborder']['game_area_id']
			);

			if (!$this->LogMobordersServerByDay->save($data)) {
				print_r($data);
				print_r($this->LogMobordersServerByDay->validationErrors);
			} else {
				$this->out('<success>Saved</success>');
			}
		}
	}

	/**
	 * This function is invalid
	 **/
	public function Niu($date)
	{
		$this->out('Running ' . $date);

		$userIds = $this->Account->find('list', array(
				'fields' => array('id', 'user_id'),
				'conditions' => array(
						'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
						'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
				)
		));

		if (empty($userIds)) {
				$this->out('Dont have new account now');
				return true;
		}

		$results = $this->LogEntergame->find('all', array(
				'fields' => array('COUNT(DISTINCT user_id) as sum', 'game_id', 'area_id'),
				'conditions' => array(
						'user_id' => $userIds,
						'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
						'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
				),
				'group' => 'game_id, area_id',
				'recursive' => -1,
		));

		if (empty($results)) {
			return true;
		}

		foreach ($results as $result) {

			if (empty($result['LogEntergame']['game_id']) || empty($result['LogEntergame']['area_id'])) {
				continue;
			}			
			$game = $this->Game->findById($result['LogEntergame']['game_id']);
			
			if (!$game) {
				$this->out('<warning>Can not find this appkey: ' . $result['LogEntergame']['app_key'] . '</warning>');
				continue;
			}

			$log = $this->LogAccountsServerByDay->find('first', array(
				'conditions' => array(
					'day' => date('Y-m-d', strtotime($date)),
					'game_id' => $game['Game']['id'],
					'area_id' => $result['LogEntergame']['area_id']
				)
			));
			if ($log) {
				$this->LogAccountsServerByDay->id = $log['LogAccountsServerByDay']['id'];
			} else {
				$this->LogAccountsServerByDay->create();
			}
			
			$data = array(
				'value' => $result[0]['sum'],
				'game_id' => $game['Game']['id'],
				'day' => date('Y-m-d', strtotime($date)),
				'area_id' => $result['LogEntergame']['area_id']
			);

			if (!$this->LogAccountsServerByDay->save($data)) {
				print_r($data);
				print_r($this->LogAccountsServerByDay->validationErrors);
			} else {
				$this->out('<success>Saved ' . $game['Game']['title'] . ', server: ' . $result['LogEntergame']['area_id'] . ', value: ' . $result[0]['sum'] . '</success>');
			}

		}	
	}

	public function Dau($date)
	{
		$this->out('Running ' . $date);

		$results = $this->LogEntergame->find('all', array(
			'fields' => array('COUNT(DISTINCT user_id) as sum', 'game_id', 'area_id'),
			'conditions' => array(
				'LogEntergame.created >=' => date('Y-m-d H:i:s', strtotime($date)),
				'LogEntergame.created < ' => date('Y-m-d H:i:s', strtotime($date) + 86400)
			),
			'group' => 'game_id, area_id',
			'recursive' => -1
		));


		foreach($results as $result) {

			if (empty($result['LogEntergame']['game_id']) || empty($result['LogEntergame']['area_id'])) {
				continue;
			}

			$game = $this->Game->findById($result['LogEntergame']['game_id']);
			
			if (!$game) {
				$this->out('<warning>Can not find this appkey: ' . $result['LogEntergame']['app_key'] . '</warning>');
				continue;
			}

			$log = $this->LogEntergamesServerByDay->find('first', array(
				'conditions' => array(
					'day' => date('Y-m-d', strtotime($date)),
					'game_id' => $game['Game']['id'],
					'area_id' => $result['LogEntergame']['area_id']
				)
			));
			if ($log) {
				$this->LogEntergamesServerByDay->id = $log['LogEntergamesServerByDay']['id'];
			} else {
				$this->LogEntergamesServerByDay->create();
			}
			
			$data = array(
				'value' => $result[0]['sum'],
				'game_id' => $game['Game']['id'],
				'day' => date('Y-m-d', strtotime($date)),
				'area_id' => $result['LogEntergame']['area_id']
			);

			if (!$this->LogEntergamesServerByDay->save($data)) {
				print_r($data);
				print_r($this->LogEntergamesServerByDay->validationErrors);
			} else {
				$this->out('<success>Saved</success>');
			}
		}
	}

}