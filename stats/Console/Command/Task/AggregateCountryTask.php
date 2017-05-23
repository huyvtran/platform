<?php

App::uses('HttpSocket', 'Network/Http');
require_once ROOT . DS . 'vendors' . DS . 'GeoIP2-php' . DS . 'vendor' . DS . 'autoload.php';
use GeoIp2\Database\Reader;

class AggregateCountryTask extends Shell {
	
	public $uses = array(
		'LogLogin', 'LogLoginsCountryByDay'
	);

	public function initialize()
	{
		parent::initialize();
		$this->Reader = new Reader(ROOT . DS . 'vendors' . DS . 'GeoIP2-php' . DS . 'GeoIP2-Country.mmdb');
	}

	public function Dau($date)
	{
        $this->out('Aggregating ...' . $date);
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
}
?>