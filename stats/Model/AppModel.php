<?php

App::uses('Model', 'Model');

class AppModel extends Model {

	public $recursive = -1;

	public $actsAs = array('Containable');
	
	# don't auto clear cache as Cakephp's default, cache will clear manually
	protected function _clearCache($type = null)
	{
		
	}
	
	function beforeSave($options = array())
	{  
		if (	empty($_SERVER['APPLICATION_ENV'])
			||	$_SERVER['APPLICATION_ENV'] != 'development'
		) {
			$this->useDbConfig = 'master';  
		}
		return true; 
	}  

	function afterSave($created)
	{
		$this->useDbConfig = 'default';  
		return true; 
	}  

	function beforeDelete($cascade = true)
	{
		if (	empty($_SERVER['APPLICATION_ENV'])
			||	$_SERVER['APPLICATION_ENV'] != 'development'
		) {			
			$this->useDbConfig = 'master';
		}
		return true; 
	}  

	function afterDelete()
	{  
	    $this->useDbConfig = 'default';  
	     return true; 
	} 

	public function beforeFind($queryData)
	{
		$this->query("SET time_zone = '+7:00';");
	}

	public function saveField($name, $value, $validate = false)
	{
		if (empty($this->id)) {
			throw new FatalErrorException('$this->id phải được set');
		}
		return parent::saveField($name, $value, $validate);
	}

    /**
     * get dates array in range date
     */
    public function getDates($from, $to, $format = 'Y-m-d', DateInterval $interval = null)
    {
        if (!$interval) {
            $interval = new DateInterval('P1D');
        }
        $start = new DateTime();
        $end = new DateTime();
        $start->setTimestamp($from);
        $end->setTimestamp($to);
        $period = new DatePeriod($start, $interval, $end);
        foreach ($period as $date) {
            $return[] = $date->format($format);
        }
        return $return;
    }

    /**
     * Filter and fill data by date ranger
     */
    public function dataToChartLine($data, $games, $from = null, $to = null)
    {
        $return = array();
        foreach($data as $v) {
            $v = $v[$this->alias];

            # if this games is not exist.
            if (!isset($games[$v['game_id']])) {
                continue;
            }
            if (!isset($return[$v['game_id']])) {
                $return[$v['game_id']]['name'] = $games[$v['game_id']];
                $return[$v['game_id']]['game_id'] = $v['game_id'];
                $return[$v['game_id']]['data'][$v['day']] = (int) $v['value'];
            } else {
                $return[$v['game_id']]['data'][$v['day']] = (int) $v['value'];
            }
        }

        if (isset($from, $to)) {
            $rangeDates = $this->getDates($from, $to);
            foreach($return as &$v) {
                foreach($rangeDates as $day) {
                    if (!isset($v['data'][$day])) {
                        $v['data'][$day] = 0;
                    }
                }
                ksort($v['data']);
                $v['data'] = array_values($v['data']);
            }
        }

        $return = array_values($return);
        return $return;
    }

    public function addLineTotal($inputArray) {
        $arrayTotal = array();
        $outputArray = array();
        $arraytmp = array(
            'game_id' => -10,
            'data' => array(),
            'name' => 'Total',
            'app_key' => '0Atotal'
        );
        $outputArray[0] = $arraytmp;
        if(empty($inputArray)){
            return $outputArray;
        }else{
            for($i = 0; $i < count($inputArray[0]['data']) ; $i++){
                $arrayTotal[$i] = 0;
            }
            foreach($inputArray as $k => $record){
                $outputArray[$k+1] = $record;
                foreach($record['data'] as $key => $value){
                    if (empty($arrayTotal[$key])) {
                        $arrayTotal[$key] = 0;
                    }
                    $arrayTotal[$key] = $arrayTotal[$key] + $value;

                }
            }
            $outputArray[0]['data'] = $arrayTotal;
            return $outputArray;
        }
    }

	/**
	 * filter data for stats by countries
	 */
	public function dataCountryToChartLine($data, $games, $from = null, $to = null)
	{
		$return = array();
		foreach($data as $v) {
			$v = $v[$this->alias];

			# if this games is not exist.
			if (!isset($games[$v['game_id']])) {
				continue;
			}
			
			if (!isset($return[$v['country']])) {
				$return[$v['country']]['name'] = $v['country'];
			}
			$return[$v['country']]['game_id'] = $v['game_id'];
			if (empty($return[$v['country']]['data'][$v['day']])) {
				$return[$v['country']]['data'][$v['day']] = 0;
			}
			$return[$v['country']]['data'][$v['day']] += (int) $v['value'];
		}

		foreach ($return as $country => &$value) {
			$value['total'] = array_sum($value['data']);
		}
		
		if (isset($from, $to)) {
			$rangeDates = $this->getDates($from, $to);
			foreach($return as &$v) {
				foreach($rangeDates as $day) {
					if (!isset($v['data'][$day])) {
						$v['data'][$day] = 0;
					}
				}
				ksort($v['data']);
				$v['data'] = array_values($v['data']);
			}
		}

		$return = array_values($return);

		if (!empty($return)) {
			$return = Hash::sort($return, '{n}.total', 'desc');
		}
		return $return;
	}

    public function dataWeekToChart($data, $games, $from = null, $to = null)
    {
        $return = array();
        foreach($data as $v) {
            $v = $v[$this->alias];

            # if this games is not exist.
            if (!isset($games[$v['game_id']])) {
                continue;
            }
            if (!isset($return[$v['game_id']])) {
                $return[$v['game_id']]['name'] = $games[$v['game_id']];
                $return[$v['game_id']]['game_id'] = $v['game_id'];
                $return[$v['game_id']]['data'][$v['day']] = (int) $v['value'];
            } else {
                $return[$v['game_id']]['data'][$v['day']] = (int) $v['value'];
            }
        }

        if (isset($from, $to)) {
            $rangeDates = $this->getDates($from, $to, 'Y-m-d', new DateInterval('P1W'));
            foreach($return as &$v) {
                foreach($rangeDates as $time) {
                    if (!isset($v['data'][$time])) {
                        $v['data'][$time] = 0;
                    }
                }
                ksort($v['data']);
                $v['data'] = array_values($v['data']);
            }
        }

        $return = array_values($return);
        return $return;
    }

    public function dataMonthToChart($data, $games, $from = null, $to = null)
    {
        $return = array();
        foreach($data as $v) {
            $v = $v[$this->alias];

            # if this games is not exist.
            if (!isset($games[$v['game_id']])) {
                continue;
            }

            if (!isset($return[$v['game_id']])) {
                $return[$v['game_id']]['name'] = $games[$v['game_id']];
                $return[$v['game_id']]['game_id'] = $v['game_id'];

                $return[$v['game_id']]['data'][$v['time']] = (int) $v['value'];
            } else {
                $return[$v['game_id']]['data'][$v['time']] = (int) $v['value'];
            }
        }

        if (isset($from, $to)) {
            $rangeDates = $this->getDates($from, $to, 'Y-m-01', new DateInterval('P1M'));

            foreach($return as &$v) {
                foreach($rangeDates as $day) {
                    if (!isset($v['data'][$day])) {
                        $v['data'][$day] = 0;
                    }
                }

                ksort($v['data']);
                $v['data'] = array_values($v['data']);
            }
        }

        $return = array_values($return);
        return $return;
    }

    public function dataQuarterToChart($data, $games, $from = null, $to = null)
    {
        $return = array();
        foreach($data as $v) {
            $v = $v[$this->alias];

            # if this games is not exist.
            if (!isset($games[$v['game_id']])) {
                continue;
            }

            if (!isset($return[$v['game_id']])) {
                $return[$v['game_id']]['name'] = $games[$v['game_id']];
                $return[$v['game_id']]['game_id'] = $v['game_id'];
            }
            $quarter = $this->date_quarter($v['day']);
            if (!isset($return[$v['game_id']]['data'][$quarter])) {
                $return[$v['game_id']]['data'][$quarter] = 0;
            }
            $return[$v['game_id']]['data'][$quarter] += (int) $v['value'];
        }
        if (isset($from, $to)) {
            $rangeDates = $this->getDates($from, $to, 'd-m-Y', new DateInterval('P3M'));
            foreach($return as &$v) {
                $q = 1;
                foreach($rangeDates as $quarter) {
                    if (!isset($v['data'][$q])) {
                        $v['data'][$q] = 0;
                    }
                    $q++;
                }
                ksort($v['data']);
                $v['data'] = array_values($v['data']);
            }
        }

        $return = array_values($return);
        return $return;
    }

    function date_quarter($day){
        return ceil(date('n', strtotime($day)) / 3);
    }

    // show query
    function getLastQuery()
    {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }
}
