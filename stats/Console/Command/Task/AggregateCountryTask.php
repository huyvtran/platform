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
            'fields' => array('count(country) as value', 'game_id', 'country'),
            'conditions' => array(
                'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
                'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
            ),
            'group' => array('game_id', 'country'),
            'recursive' => -1,
        ));
        if (empty($logInstalls)) {
            return true;
        }

        foreach ($logInstalls as $log) {
            $countryName = $this->__getCountryName($log['LogInstall']['country']);
            $logs[$log['LogInstall']['game_id']]
            [$countryName] = $log[0]['value'];
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

    private function __getCountryName($countryCode){
	    $data = '{"BD": "Bangladesh", "BE": "Belgium", "BF": "Burkina Faso", "BG": "Bulgaria", "BA": "Bosnia and Herzegovina", "BB": "Barbados", "WF": "Wallis and Futuna", "BL": "Saint Barthelemy", "BM": "Bermuda", "BN": "Brunei", "BO": "Bolivia", "BH": "Bahrain", "BI": "Burundi", "BJ": "Benin", "BT": "Bhutan", "JM": "Jamaica", "BV": "Bouvet Island", "BW": "Botswana", "WS": "Samoa", "BQ": "Bonaire, Saint Eustatius and Saba ", "BR": "Brazil", "BS": "Bahamas", "JE": "Jersey", "BY": "Belarus", "BZ": "Belize", "RU": "Russia", "RW": "Rwanda", "RS": "Serbia", "TL": "East Timor", "RE": "Reunion", "TM": "Turkmenistan", "TJ": "Tajikistan", "RO": "Romania", "TK": "Tokelau", "GW": "Guinea-Bissau", "GU": "Guam", "GT": "Guatemala", "GS": "South Georgia and the South Sandwich Islands", "GR": "Greece", "GQ": "Equatorial Guinea", "GP": "Guadeloupe", "JP": "Japan", "GY": "Guyana", "GG": "Guernsey", "GF": "French Guiana", "GE": "Georgia", "GD": "Grenada", "GB": "United Kingdom", "GA": "Gabon", "SV": "El Salvador", "GN": "Guinea", "GM": "Gambia", "GL": "Greenland", "GI": "Gibraltar", "GH": "Ghana", "OM": "Oman", "TN": "Tunisia", "JO": "Jordan", "HR": "Croatia", "HT": "Haiti", "HU": "Hungary", "HK": "Hong Kong", "HN": "Honduras", "HM": "Heard Island and McDonald Islands", "VE": "Venezuela", "PR": "Puerto Rico", "PS": "Palestinian Territory", "PW": "Palau", "PT": "Portugal", "SJ": "Svalbard and Jan Mayen", "PY": "Paraguay", "IQ": "Iraq", "PA": "Panama", "PF": "French Polynesia", "PG": "Papua New Guinea", "PE": "Peru", "PK": "Pakistan", "PH": "Philippines", "PN": "Pitcairn", "PL": "Poland", "PM": "Saint Pierre and Miquelon", "ZM": "Zambia", "EH": "Western Sahara", "EE": "Estonia", "EG": "Egypt", "ZA": "South Africa", "EC": "Ecuador", "IT": "Italy", "VN": "Vietnam", "SB": "Solomon Islands", "ET": "Ethiopia", "SO": "Somalia", "ZW": "Zimbabwe", "SA": "Saudi Arabia", "ES": "Spain", "ER": "Eritrea", "ME": "Montenegro", "MD": "Moldova", "MG": "Madagascar", "MF": "Saint Martin", "MA": "Morocco", "MC": "Monaco", "UZ": "Uzbekistan", "MM": "Myanmar", "ML": "Mali", "MO": "Macao", "MN": "Mongolia", "MH": "Marshall Islands", "MK": "Macedonia", "MU": "Mauritius", "MT": "Malta", "MW": "Malawi", "MV": "Maldives", "MQ": "Martinique", "MP": "Northern Mariana Islands", "MS": "Montserrat", "MR": "Mauritania", "IM": "Isle of Man", "UG": "Uganda", "TZ": "Tanzania", "MY": "Malaysia", "MX": "Mexico", "IL": "Israel", "FR": "France", "IO": "British Indian Ocean Territory", "SH": "Saint Helena", "FI": "Finland", "FJ": "Fiji", "FK": "Falkland Islands", "FM": "Micronesia", "FO": "Faroe Islands", "NI": "Nicaragua", "NL": "Netherlands", "NO": "Norway", "NA": "Namibia", "VU": "Vanuatu", "NC": "New Caledonia", "NE": "Niger", "NF": "Norfolk Island", "NG": "Nigeria", "NZ": "New Zealand", "NP": "Nepal", "NR": "Nauru", "NU": "Niue", "CK": "Cook Islands", "XK": "Kosovo", "CI": "Ivory Coast", "CH": "Switzerland", "CO": "Colombia", "CN": "China", "CM": "Cameroon", "CL": "Chile", "CC": "Cocos Islands", "CA": "Canada", "CG": "Republic of the Congo", "CF": "Central African Republic", "CD": "Democratic Republic of the Congo", "CZ": "Czech Republic", "CY": "Cyprus", "CX": "Christmas Island", "CR": "Costa Rica", "CW": "Curacao", "CV": "Cape Verde", "CU": "Cuba", "SZ": "Swaziland", "SY": "Syria", "SX": "Sint Maarten", "KG": "Kyrgyzstan", "KE": "Kenya", "SS": "South Sudan", "SR": "Suriname", "KI": "Kiribati", "KH": "Cambodia", "KN": "Saint Kitts and Nevis", "KM": "Comoros", "ST": "Sao Tome and Principe", "SK": "Slovakia", "KR": "South Korea", "SI": "Slovenia", "KP": "North Korea", "KW": "Kuwait", "SN": "Senegal", "SM": "San Marino", "SL": "Sierra Leone", "SC": "Seychelles", "KZ": "Kazakhstan", "KY": "Cayman Islands", "SG": "Singapore", "SE": "Sweden", "SD": "Sudan", "DO": "Dominican Republic", "DM": "Dominica", "DJ": "Djibouti", "DK": "Denmark", "VG": "British Virgin Islands", "DE": "Germany", "YE": "Yemen", "DZ": "Algeria", "US": "United States", "UY": "Uruguay", "YT": "Mayotte", "UM": "United States Minor Outlying Islands", "LB": "Lebanon", "LC": "Saint Lucia", "LA": "Laos", "TV": "Tuvalu", "TW": "Taiwan", "TT": "Trinidad and Tobago", "TR": "Turkey", "LK": "Sri Lanka", "LI": "Liechtenstein", "LV": "Latvia", "TO": "Tonga", "LT": "Lithuania", "LU": "Luxembourg", "LR": "Liberia", "LS": "Lesotho", "TH": "Thailand", "TF": "French Southern Territories", "TG": "Togo", "TD": "Chad", "TC": "Turks and Caicos Islands", "LY": "Libya", "VA": "Vatican", "VC": "Saint Vincent and the Grenadines", "AE": "United Arab Emirates", "AD": "Andorra", "AG": "Antigua and Barbuda", "AF": "Afghanistan", "AI": "Anguilla", "VI": "U.S. Virgin Islands", "IS": "Iceland", "IR": "Iran", "AM": "Armenia", "AL": "Albania", "AO": "Angola", "AQ": "Antarctica", "AS": "American Samoa", "AR": "Argentina", "AU": "Australia", "AT": "Austria", "AW": "Aruba", "IN": "India", "AX": "Aland Islands", "AZ": "Azerbaijan", "IE": "Ireland", "ID": "Indonesia", "UA": "Ukraine", "QA": "Qatar", "MZ": "Mozambique"}';
	    $data = json_decode($data, true);
	    $name = 'Unknown';
	    if( !empty($data[$countryCode]) ) $name = $data[$countryCode];
	    return $name;
    }
}
?>