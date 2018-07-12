<?php

App::uses('HttpSocket', 'Network/Http');
require_once ROOT . DS . 'vendors' . DS . 'GeoIP2-php' . DS . 'vendor' . DS . 'autoload.php';
use GeoIp2\Database\Reader;

class AggregateCountryTask extends Shell {
	
	public $uses = array(
		'LogLogin', 'LogLoginsCountryByDay',
        'Payment', 'LogPaymentsCountryByDay',
        'Account', 'LogAccountsCountryByDay',
        'LogInstall', 'LogInstallCountryByDay'
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

	public function Revenue($date)
	{
		$payments = $this->Payment->find('all', array(
			'fields' => array('user_id', 'game_id', 'SUM(price_end) as sum'),
			'conditions' => array(
				'time >= ' => strtotime($date),
				'time <= ' => strtotime($date) + 86399,
				'Payment.test' => 0
			),
			'group' => array('user_id'),
			'recursive' => -1,
		));
		if (empty($payments))
			return true;

		$userIds = Hash::extract($payments, '{n}.Payment.user_id');

		$logLogins = $this->LogLogin->find('list', array(
			'fields' => array('user_id', 'ip'),
			'conditions' => array(
				'user_id' => $userIds,
				'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
				'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
			)
		));

		foreach($payments as $order) {
            $country = 'Unknown';
			if ( !empty($logLogins[$order['Payment']['user_id']])) {
                $ip = $logLogins[$order['Payment']['user_id']];
                try {
                    $record = $this->Reader->country($ip);
                    $country = $record->country->names['en'];
                } catch (GeoIp2\Exception\AddressNotFoundException $e) {
                    $country = 'Unknown';
                } catch (Exception $e) {
                    $country = 'Unknown';
                }
			}
			if($country == 'Unknown'){
                $this->Payment->User->recursive = -1;
                $user = $this->Payment->User->findById($order['Payment']['user_id'], array('country_code'));
                if(!empty($user['User']['country_code'])) $country = $user['User']['country_code'];
            }

			$gameId = $order['Payment']['game_id'];
			if (empty($logs[$gameId][$country])) {
				$logs[$gameId][$country] = 0;
			}

			# xử lý all game
            if (empty($logs[999999999][$country])) {
                $logs[999999999][$country] = 0;
            }

			$logs[$gameId][$country] += $order[0]['sum'];
			$logs[999999999][$country]  += $order[0]['sum'];
		}
		if (empty($logs)) {
			return true;
		}

		foreach ($logs as $gameId => $log) {
			foreach($log as $country => $count) {
				$existed = $this->LogPaymentsCountryByDay->find('first', array('conditions' => array(
					'game_id' => $gameId,
					'country' => $country,
					'day' => date('Y-m-d', strtotime($date))
				)
				));

				if (empty($existed)) {
					$this->LogPaymentsCountryByDay->create();
					$this->LogPaymentsCountryByDay->save(array(
						'game_id' => $gameId,
						'value' => $count,
						'country' => $country,
						'day' => date('Y-m-d', strtotime($date))
					));
					$this->out('<success>Created</success>');
				} else {
					$this->LogPaymentsCountryByDay->save(array(
						'id' => $existed['LogPaymentsCountryByDay']['id'],
						'game_id' => $gameId,
						'value' => $count,
						'country' => $country,
						'day' => date('Y-m-d', strtotime($date))
					));
					$this->out('<success>Saved</success>');
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
                    $this->out('Updated');
                }
            }
        }
    }

    public function Install($date)
    {
        $this->out('Aggregating ...' . $date);
        # don't use "group by" to avoid mysql bad performance , just for now
        $logInstalls = $this->LogInstall->find('all', array(
            'fields' => array('DISTINCT device_id', 'game_id', 'ip'),
            'conditions' => array(
                'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
                'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
            ),
            'recursive' => -1,
        ));
        if (empty($logInstalls)) {
            return true;
        }
        foreach ($logInstalls as $key => $log) {
            $logsTemp   [$log['LogInstall']['game_id']]
            [$log['LogInstall']['device_id']] = $log['LogInstall']['ip'];
        }

        foreach ($logsTemp as $gameId => $log) {
            foreach($log as $device_id => $ip) {
                try {
                    $record = $this->Reader->country($ip);
                    $country = $record->country->names['en'];
                } catch (GeoIp2\Exception\AddressNotFoundException $e) {
                    $country = 'Unknown';
                }  catch (Exception $e) {
                    $country = 'Unknown';
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
                $existed = $this->LogInstallCountryByDay->find('first', array('conditions' => array(
                    'game_id' => $gameId,
                    'country' => $country,
                    'day' => date('Y-m-d', strtotime($date))
                )
                ));

                if (empty($existed)) {
                    $this->LogInstallCountryByDay->create();
                    $this->LogInstallCountryByDay->save(array(
                        'game_id' => $gameId,
                        'value' => $count,
                        'country' => $country,
                        'day' => date('Y-m-d', strtotime($date))
                    ));
                    $this->out('Created');
                } else {
                    $this->LogInstallCountryByDay->save(array(
                        'id' => $existed['LogInstallCountryByDay']['id'],
                        'game_id' => $gameId,
                        'value' => $count,
                        'country' => $country,
                        'day' => date('Y-m-d', strtotime($date))
                    ));
                    $this->out('Saved');
                }
            }
        }
    }
}
?>