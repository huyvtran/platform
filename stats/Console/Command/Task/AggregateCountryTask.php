<?php

App::uses('HttpSocket', 'Network/Http');
require_once ROOT . DS . 'vendors' . DS . 'GeoIP2-php' . DS . 'vendor' . DS . 'autoload.php';
use GeoIp2\Database\Reader;

class AggregateCountryTask extends Shell {
	
	public $uses = array(
		'LogLogin', 'Moborder', 'Account',
		'Game', 'User',
		'LogAccountsCountryByDay', 'LogLoginsCountryByDay',
		'LogMobordersCountryByDay', 'LogArpuCountryByMonth',
		'LogMobordersCountryTypeByDay'
	);

	public function initialize()
	{
		parent::initialize();
		$this->Reader = new Reader(ROOT . DS . 'vendors' . DS . 'GeoIP2-php' . DS . 'GeoIP2-Country.mmdb');
	}

	public function Arpu($date)
	{

		# don't use "group by" to avoid mysql bad performance , just for now
		$logLogins = $this->LogLogin->find('all', array(
			'fields' => array('user_id', 'game_id', 'ip'),
			'conditions' => array(
				'created >= ' => date('Y-m-01 00:00:00', strtotime($date)),
				'created <= ' => date('Y-m-t 23:59:59', strtotime($date)),
				'game_id >= ' => 10,
			),
			'group' => array('game_id', 'user_id'),
			'recursive' => -1,
		));

		foreach ($logLogins as $key => $value) {
			$ip = $value['LogLogin']['ip'];
			try {
				$record = $this->Reader->country($ip);
				$country = $record->country->names['en'];
			} catch (GeoIp2\Exception\AddressNotFoundException $e) {
				$country = 'Unknown';
			} catch (Exception $e) {
				continue;
			}
			if (empty($daus[$value['LogLogin']['game_id']][$country])) {
				$daus[$value['LogLogin']['game_id']][$country] = 0;
			}
			$daus[$value['LogLogin']['game_id']][$country]++;	
		}

		$logs = $this->LogMobordersCountryByDay->find('all', array(
			'conditions' => array(
				'created >= ' => date('Y-m-01 00:00:00', strtotime($date)),
				'created <= ' => date('Y-m-t 23:59:59', strtotime($date)),
			),
			'recursive' => -1
		));

		foreach ($logs as $key => $value) {
			$gameId = $value['LogMobordersCountryByDay']['game_id'];
			$country = $value['LogMobordersCountryByDay']['country'];
			if (empty($revenues[$gameId][$country])) {
				$revenues[$gameId][$country] = 0;
			}
			$revenues[$gameId][$country] += $value['LogMobordersCountryByDay']['value'];
		}

		foreach ($daus as $gameId => $v) {
			foreach ($v as $country => $count) {
				if (empty($revenues[$gameId][$country])) {
					$arpu = 0;
				} else {
					$arpu = $revenues[$gameId][$country] / $count;
				}

				$existed = $this->LogArpuCountryByMonth->find('first', array(
					'conditions' => array(
						'country' => $country,
						'month' => date('Y-m-01', strtotime($date)),
						'game_id' => $gameId
					)
				));

				if (empty($existed)) {
					$this->LogArpuCountryByMonth->create();
					$this->LogArpuCountryByMonth->save(array(
						'country' => $country,
						'value' => $arpu,
						'month' => date('Y-m-01', strtotime($date)),
						'game_id' => $gameId,
						'revenues' => empty($revenues[$gameId][$country]) ? 0 : $revenues[$gameId][$country],
						'dau' => $count
					));
				} else {
					$this->LogArpuCountryByMonth->save(array(
						'id' => $existed['LogArpuCountryByMonth']['id'],
						'country' => $country,
						'value' => $arpu,
						'month' => date('Y-m-01', strtotime($date)),
						'game_id' => $gameId,
						'revenues' => empty($revenues[$gameId][$country]) ? 0 : $revenues[$gameId][$country],
						'dau' => $count
					));
				}
			}
		}
	}

	public function Niu($date)
	{	
		$accounts = $this->Account->find('all', array(
			'fields' => array('id', 'user_id', 'game_id'),
			'conditions' => array(
				'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
				'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
				'game_id >= ' => 10,
			)
		));
		if (empty($accounts)) {
			$this->out('Dont have new account now');
			return true;
		}
		$userIds = Hash::extract($accounts, '{n}.Account.user_id');

		
		$logLogins = $this->LogLogin->find('list', array(
			'fields' => array('user_id', 'ip'),
			'conditions' => array(
				'user_id' => $userIds,
				'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
				'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
				'game_id >= ' => 10,
			),
			'recursive' => -1,
		));

		foreach($accounts as $account) {
			if (empty($logLogins[$account['Account']['user_id']])) {
				$country = 'Unknown';
			} else {
				$ip = $logLogins[$account['Account']['user_id']];

				try {
					$record = $this->Reader->country($ip);
					$country = $record->country->names['en'];
				} catch (GeoIp2\Exception\AddressNotFoundException $e) {
					$country = 'Unknown';
				} catch (Exception $e) {
					continue;
				}
			}

			if (empty($logs[$account['Account']['game_id']][$country])) {
				$logs[$account['Account']['game_id']][$country] = 0;
			}
			$logs[$account['Account']['game_id']][$country]++;
		}
		if (empty($logs)) {
			return true;
		}
		foreach ($logs as $gameId => $log) {
			foreach($log as $country => $count) {
				$existed = $this->LogAccountsCountryByDay->find('first', array('conditions' => array(
						'game_id' => $gameId,
						'country' => $country,
						'day' => date('Y-m-d', strtotime($date))
					)
				));

				if (empty($existed)) {
					$this->LogAccountsCountryByDay->create();
					$this->LogAccountsCountryByDay->save(array(
							'game_id' => $gameId,
							'value' => $count,
							'country' => $country,
							'day' => date('Y-m-d', strtotime($date))
					));
					$this->out('Saved');
				} else {

					$this->LogAccountsCountryByDay->save(array(
							'id' => $existed['LogAccountsCountryByDay']['id'],
							'game_id' => $gameId,
							'value' => $count,
							'country' => $country,
							'day' => date('Y-m-d', strtotime($date))
					));
				}
			} 
		}
	}

	public function Dau($date)
	{
		# don't use "group by" to avoid mysql bad performance , just for now
		$logLogins = $this->LogLogin->find('all', array(
			'fields' => array('DISTINCT user_id', 'game_id', 'ip'),
			'conditions' => array(
				'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
				'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
			),
			'recursive' => -1,
		));
		if (empty($logLogins)) {
			return true;
		}
		$ips = Hash::extract($logLogins, '{n}.LogLogin.ip');

		foreach ($logLogins as $key => $log) {
			$logsTemp   [$log['LogLogin']['game_id']]
						[$log['LogLogin']['user_id']] = $log['LogLogin']['ip'];
		}

		foreach ($logsTemp as $gameId => $log) {
			foreach($log as $userId => $ip) {
				try {
					$record = $this->Reader->country($ip);
					$country = $record->country->names['en'];
				} catch (GeoIp2\Exception\AddressNotFoundException $e) {
					$country = 'Unknown';
				}  catch (Exception $e) {
					continue;
				}

				if (empty($logs[$gameId][$country])) {
					$logs[$gameId][$country] = 0;
				}
				$logs[$gameId][$country]++;
			}
		}

		if (empty($logs)) {
			return true;
		}
		
		foreach ($logs as $gameId => $log) {
			foreach($log as $country => $count) {
				$existed = $this->LogLoginsCountryByDay->find('first', array('conditions' => array(
						'game_id' => $gameId,
						'country' => $country,
						'day' => date('Y-m-d', strtotime($date))
					)
				));

				if (empty($existed)) {
					$this->LogLoginsCountryByDay->create();
					$this->LogLoginsCountryByDay->save(array(
							'game_id' => $gameId,
							'value' => $count,
							'country' => $country,
							'day' => date('Y-m-d', strtotime($date))
					));
					$this->out('Saved');
				} else {

					$this->LogLoginsCountryByDay->save(array(
							'id' => $existed['LogLoginsCountryByDay']['id'],
							'game_id' => $gameId,
							'value' => $count,
							'country' => $country,
							'day' => date('Y-m-d', strtotime($date))
					));
				}
			} 
		}
	}

	public function Revenue($date)
	{
		$appKeyToGameId = $this->Game->find('list', array(
			'fields' => array('app_key', 'id'),
			'conditions' => array('id >=' => 10),
			'recursive' => -1
		));		

		$orders = $this->Moborder->find('all', array(
			'fields' => array('user_id', 'app_key', 'SUM(platform_price) as sum'),
			'conditions' => array(
				'time >= ' => strtotime($date),
				'time <= ' => strtotime($date) + 86399,
				'app_key' => array_keys($appKeyToGameId),
				'Moborder.test_type' => 0
			),
			'group' => array('user_id'),
			'recursive' => -1,
		));
		if (empty($orders))
			return true;
		$userIds = Hash::extract($orders, '{n}.Moborder.user_id');

		$logLogins = $this->LogLogin->find('list', array(
			'fields' => array('user_id', 'ip'),
			'conditions' => array(
				'user_id' => $userIds,
				'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
				'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
				'game_id >= ' => 10,
			)
		));

		foreach($orders as $order) {
			if (empty($logLogins[$order['Moborder']['user_id']])) {
				continue;
			}			
			$ip = $logLogins[$order['Moborder']['user_id']];
			try {
				$record = $this->Reader->country($ip);
				$country = $record->country->names['en'];
			} catch (GeoIp2\Exception\AddressNotFoundException $e) {
				$country = 'Unknown';
			}  catch (Exception $e) {
				continue;
			}

			$gameId = $appKeyToGameId[$order['Moborder']['app_key']];
			if (empty($logs[$gameId][$country])) {
				$logs[$gameId][$country] = 0;
			}

			$logs[$gameId][$country] += $order[0]['sum'];
		}
		if (empty($logs)) {
			return true;
		}

		foreach ($logs as $gameId => $log) {
			foreach($log as $country => $count) {
				$existed = $this->LogMobordersCountryByDay->find('first', array('conditions' => array(
						'game_id' => $gameId,
						'country' => $country,
						'day' => date('Y-m-d', strtotime($date))
					)
				));

				if (empty($existed)) {
					$this->LogMobordersCountryByDay->create();
					$this->LogMobordersCountryByDay->save(array(
							'game_id' => $gameId,
							'value' => $count,
							'country' => $country,
							'day' => date('Y-m-d', strtotime($date))
					));
					$this->out('Saved');
				} else {

					$this->LogMobordersCountryByDay->save(array(
							'id' => $existed['LogMobordersCountryByDay']['id'],
							'game_id' => $gameId,
							'value' => $count,
							'country' => $country,
							'day' => date('Y-m-d', strtotime($date))
					));
				}
			} 
		}
	}

	/**
	 * This function don't use now
	 **/
	public function ipsToCountries($date)
	{
		$ips = $this->LogLogin->find('list', array(
			'fields' => array('ip', 'id'),
			'conditions' => array(
				'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
				'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
				'game_id >=' => 10,
			),
			'recursive' => -1
		));

		if (!empty($ips)) {
			$HttpSocket = new HttpSocket();
			$HttpSocket->configAuth('Basic', 'xxxx', 'xxxx');

			foreach ($ips as $ip => $id) {
				if ($existed = $this->IpsCountry->find('first', array(
					'conditions' => array('ip' => $ip),
					'recursive' => -1
				))) {
					continue;
				}
				$res = $HttpSocket->get('https://geoip.maxmind.com/geoip/v2.1/country/' . $ip);
				if ($res->code == '200') {
					$result = json_decode($res->body, true);
					if (!empty($result['country']['iso_code'])) {
						$this->out($ip . ":" . $result['country']['iso_code']);
						$this->IpsCountry->create();
						$this->IpsCountry->save(array(
							'IpsCountry' => array(
								'ip' => $ip,
								'country_code' => $result['country']['names']['en']
							)
						));
					} else {
						CakeLog::error('ip to country (couldnt find isocode): ' . print_r($res->code, true) . ' body: ' . print_r($res->body, true));
					}
					
				} else {
					CakeLog::error('ip to country: ' . print_r($res->code, true) . ' body: ' . print_r($res->body, true));
				}

			}
		} else {
			$this->out('Dont have new users');
		}
	}

	/**
	 * This function update record revenue by country and type
	 **/
	public function RevenueByCountryType($date){
		
		$appKeyToGameId = $this->Game->find('list', array(
			'fields' => array('app_key', 'id'),
			'recursive' => -1
		));

		$orders = $this->Moborder->find('all', array(
			'fields' => array('user_id', 'app_key', 'type', 'SUM(platform_price) as sum', 'User.country_code'),
			'joins' => array(array('table' => 'users',
									'alias' => 'User',
									'type' => 'INNER',
									'conditions' => array('Moborder.user_id = User.id'))),
			'conditions' => array(
				'time >= ' => strtotime($date),
				'time <= ' => strtotime($date) + 86399,
				'Moborder.test_type' => 0
			),
			'group' => array('app_key', 'type', 'User.country_code'),
			'recursive' => -1,
		));

		if (empty($orders))
			return true;

		foreach($orders as $order) {

			if(is_null($order['User']['country_code']) ||  $order['User']['country_code'] == ''){
				$country = 'isNull';
			}else{
				$country = $order['User']['country_code'];
			}

			$gameId = $appKeyToGameId[$order['Moborder']['app_key']];
			$type = $order['Moborder']['type'];
			
			$existed = $this->LogMobordersCountryTypeByDay->find('first', array(
				'conditions' => array(
					'game_id' => $gameId,
					'country' => $country,
					'type' => $type,
					'day' => date('Y-m-d', strtotime($date))
				),
				'recursive' => -1
			));
			// var_dump($existed);
			
			if (empty($existed)) {
				$this->LogMobordersCountryTypeByDay->create();
				if($this->LogMobordersCountryTypeByDay->save(array(
							'value' => $order[0]['sum'],
							'game_id' => $gameId,
							'country' => $country,
							'day' => date('Y-m-d', strtotime($date)),
							'type' => $type
					))){
					$this->out('Created->saved');
				}else{
					$this->out('Created->not save');
				}
				
				$this->out('Saved');
			} else {
				$this->LogMobordersCountryTypeByDay->save(array(
						'id' => $existed['LogMobordersCountryTypeByDay']['id'],
						'game_id' => $gameId,
						'value' => $order[0]['sum'],
						'country' => $country,
						'type' => $type,
						'day' => date('Y-m-d', strtotime($date))
				));
			}
		}
	}
	//end RevenueByCountryType
}
?>