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

	public function convertToUSD($data, $precise = false)
	{
		foreach($data as &$vv) {
			foreach($vv['data'] as &$v) {
				if ($precise && $v % 100 != 0) {
					$v = (float) round($v / 100, 2);
				} else {
					if (is_numeric($precise) && $v % 100 != 0) {
						$v = (float) round($v / 100, $precise);
					} else {

						$v = (int) round($v / 100);
					}
				}
			}
		}

		return $data;
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

	/**
	 * $data not conjunction with games
	 */
	public function dataToChartLine3($data, $fields = array(), $from = null, $to = null)
	{
		$tmp = array();
		
		foreach ($data as $v) {
			foreach($v[$this->alias] as $kk => $vv) {
				if (array_search($kk, $fields) !== false) {
					$tmp[$fields[array_search($kk, $fields)]][$v[$this->alias]['day']] = $vv;
				}

			}
		}

		foreach($tmp as $key => $v) {

			$return[] = array(
				'name' => $key,
				'data' => $v
			);
		}
		
		if (empty($return)) {
			return array();
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

	public function mergeDataSimilarGame($data, $name = false)
	{
		$Game = ClassRegistry::init('Game');
		$games = $Game->find('all', array('recursive' => -1));
		$similarIds = array();
		foreach($games as $game) {
			$similarIds[$game['Game']['alias']][] = $game['Game']['id'];
		}
		$tmp = $data;
		$result = array();
		foreach($data as $k => &$v) {
			foreach ($similarIds as $key => $ids) {
				if (in_array($v['game_id'], $ids)) {
					foreach($tmp as $kk => &$vv)  {
						if (	$v['game_id'] == $vv['game_id']
							||	($name == true && $v['name'] != $vv['name'])
						) {
							continue;
						}

						if (in_array($vv['game_id'], $ids)) {
							foreach($v['data'] as $kkk => $vvv) {
								$v['data'][$kkk] += $vv['data'][$kkk];
							}
							unset($tmp[$kk]);
							unset($data[$kk]);
						}
					}
				}
			}
			$result[] = $v;
		}
		return $result;
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

	protected function _fillData($result, $rangerDate)
	{
		foreach($result as $k => $data) {
			$result[$k]['data'] = Hash::merge(array_fill_keys($rangerDate, 0), $data['data']);
		}
		return $result;
	}

	public function dataCountryTypeToChartLine($data, $games, $from = null, $to = null)
	{
		$return = array();
		foreach($data as $v) {

			$v = $v[$this->alias];

			# if this games is not exist.
			if (!isset($games[$v['game_id']])) {
				continue;
			}
			
			if (!isset($return[$v['type']])) {
				$return[$v['type']]['name'] = $v['type'];
			}
			$return[$v['type']]['game_id'] = $v['game_id'];
			if (empty($return[$v['type']]['data'][$v['day']])) {
				$return[$v['type']]['data'][$v['day']] = 0;
			}
			$return[$v['type']]['data'][$v['day']] += (int) $v['value'];
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

}
