<?php
App::uses('HttpSocket', 'Network/Http');

class AggregateBaseTask extends Shell {

	/**
	 * If no arguments is provided, this method'll aggregate data in today.
	 * @param string $modelName name of model to aggregate.
	 * @param string $modelSave name of model to save after when run aggregate.
	 * @param string $function aggregation function : SUM, COUNT, AVG, COUNT_DISTINCT
	 * @param string $field field that use above function
	 * @param string $options:
	 * 				- fields: you want to group by when aggregate.
	 * 				- conditions: (optional)
	 * 				- app_key: (optional) convert app_key to game_id
	 * 				- day: day to aggregate
	 */
	public function _aggreateByDay($modelName, $modelSave, $function, $field, $aggregateFields = array(), $options = array())
	{
		$this->out($modelName);
		# convert aggregation function to real field
		$realfunction = sprintf("$function(%s)", $field);
		if ($function == 'COUNT_DISTINCT') {
			$realfunction = sprintf('COUNT(DISTINCT %s)', $field);
		}

		# if 4th argument is date [d-m-Y]
		$currentTime = time();
		if (!empty($options['day'])) {
			$currentTime = strtotime($options['day']);
		}

		App::import('Model', $modelName);
		App::import('Model', $modelSave);
		$Model = new $modelName();
		$Model2 = new $modelSave();

		$this->out('Aggregating ...' . date('d-m-Y', $currentTime));
		$fields = am($aggregateFields, array("$realfunction as $function"));

		if (!empty($options['conditions'])) {
			$conditions = $options['conditions'];
		} else {
			$conditions = CakeTime::dayAsSql($currentTime, "$modelName.created");
		}

		$group = $aggregateFields;

		$results = $Model->find('all', compact('fields', 'conditions', 'group'));

		if (!empty($results)) {
			foreach ($results as $k => $v) {

				$value = current($v['0']);
				$temp = $v[$modelName];

				unset($temp[$field]);
				$temp['day'] = date('Y-m-d', $currentTime);

				$existed = $Model2->find('first', array(
					'conditions' => $temp,
					'recursive' => -1
				));

				if (!empty($existed)) {
					$Model2->id = $existed[$modelSave]['id'];
				} else {
					$Model2->create();
				}
				$data = am($v[$modelName], array('day' => date('Y-m-d', $currentTime), 'value' => $value));

				if (!$Model2->save($data)) {
					print_r($data);
					print_r($Model2->validationErrors);
					$this->error("Unable to save data.");
				}
			}
		} else {
			$this->out('<warning>No record was found</warning>');
		}
		$this->out('Done!');
	}

    /**
     * If no arguments is provided, this method'll aggregate data in current point of time (this month).
     *
     * @param string|array		model to aggregate
     * 								- name
     * 								- timeField = 'time' (optional)
     * @param string|array		model to save after when run aggregate
     * 								- name
     * 								- timeField = 'time' (optional)
     * @param string			aggregation function (SUM | COUNT | AVG | COUNT_DISTINCT)
     * @param string			field that used in group function. eg: SUM($field)
     * @param array				additional options
     * 								- time: time to aggregate (d-m-Y)
     * 								- conditions (optional)
     */
    public function _aggreateByMonth($model, $model2, $function, $field, $group = array(), $options = array())
    {
        $defaults = array(
            'name' => null,
            'timeField' => 'time',
        );

        $model = !is_array($model) ? array('name' => $model) : $model;
        $model = am($defaults, $model);
        $modelName = $model['name'];
        $timeField = $model['timeField'];

        $model2 = !is_array($model2) ? array('name' => $model2) : $model2;
        $model2 = am($defaults, $model2);
        $modelName2 = $model2['name'];
        $timeField2 = $model2['timeField'];

        App::import('Model', $model['name']);
        App::import('Model', $model2['name']);
        $Model = new $modelName();
        $Model2 = new $modelName2();

        $this->out("Monthly.....");
        $this->out($modelName);

        # convert aggregation function to real field
        $groupfunction = sprintf("$function(%s)", $field);
        if ($function == 'COUNT_DISTINCT') {
            $groupfunction = sprintf('COUNT(DISTINCT %s)', $field);
        }

        # if 4th argument is date [d-m-Y]

        $timestamp = time();
        if (isset($options['time'])) {
            $timestamp = strtotime($options['time']);
        }

        $this->out('Aggregating ...' . date('Y-m', $timestamp));

        $fields = am($group, array(' DATE_FORMAT(' . $timeField . ', "%Y-%m-01") as ' . $timeField2, "$groupfunction as $function"));

        if (!empty($options['conditions'])) {
            $conditions = $options['conditions'];
        } else {
            $conditions = array(
                $timeField . " >= " => date('Y-m-01', $timestamp),
                $timeField . " <= " => date('Y-m-t', $timestamp)
            );

        }

        $results = $Model->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions,
            'group' => $group,
            'recursive' => -1
        ));


        if (!empty($results)) {
            foreach ($results as $k => $v) {

                $existed = $Model2->find('first', array(
                    'conditions' => array($timeField2 => $v[0][$timeField2], 'game_id' => $v[$modelName]['game_id']),
                    'recursive' => -1
                ));

                if (!empty($existed)) {
                    $Model2->id = $existed[$modelName2]['id'];
                } else {
                    $Model2->create();
                }

                $data = am($v[$modelName], array('value' => $v[0][$function], $timeField2 => $v[0][$timeField2]));

                if (!$Model2->save($data)) {
                    print_r($data);
                    print_r($Model2->validationErrors);
                    $this->error("Unable to save data.");
                }
            }
        } else {
            $this->out('<warning>No record was found</warning>');
        }
        $this->out('Done!');
    }

    public function _retention($date)
    {
        $this->out('Start Retention ' . $date);
        set_time_limit(1000);
        ini_set('memory_limit', '512M');

        App::import('Model', 'Game');
        App::import('Model', 'LogLogin');
        App::import('Model', 'Account');
        App::import('Model', 'LogRetention');

        $Game = new Game();
        $Account = new Account();
        $LogLogin = new LogLogin();
        $Log = new LogRetention();

        $games = $Game->find('all', array(
            'recursive' => -1,
            'conditions' => array('status' => 1)
        ));

        foreach ($games as $game) {
            $retention = array();
            foreach (array(1, 3, 7, 30) as $range) {

                $nius = $Account->find('list', array(
                    'fields' => array('id', 'user_id'),
                    'conditions' => array(
                        'created >= ' => date('Y-m-d 00:00:00', strtotime("-$range days", strtotime($date))),
                        'created <= ' => date('Y-m-d 23:59:59', strtotime("-$range days", strtotime($date))),
                        'game_id' => $game['Game']['id']),
                    'recursive' => -1
                ));
                if (!$nius) {
                    continue;
                }

                $this->out('<warning>Running game: ' . $game['Game']['title'] . ' - range date: '  . $range . '</warning>');

                $daus = $LogLogin->find('list', array(
                    'fields' => array('id', 'user_id'),
                    'conditions' => array(
                        'created >= ' => date('Y-m-d 00:00:00', strtotime($date)),
                        'created <= ' => date('Y-m-d 23:59:59', strtotime($date)),
                        'game_id' => $game['Game']['id']),
                    'recursive' => -1
                ));

                $r = 0;
                if (!empty($daus)) {
                    $r = count(array_intersect($nius, $daus));
                }
                if ($r == 0) {
                    continue;
                }
                $retention['return' . $range] = $r;
                $retention['reg' . $range] = count($nius);
            }

            if (empty($retention)) {
                continue;
            }
            $log = $Log->find('first', array(
                'conditions' => array(
                    'game_id' => $game['Game']['id'],
                    'day' => date('Y-m-d', strtotime($date))
                )
            ));

            if ($log) {
                $Log->id = $log['LogRetention']['id'];
            } else {
                $Log->create();
            }
            $data = array_merge(array('game_id' => $game['Game']['id'], 'day' => date('Y-m-d', strtotime($date))), $retention);
            if (!$Log->save($data)) {
                print_r($data);
                print_r($Log->validationErrors);
            } else {
                $this->out('<success>Saved</success>');
            }
        }
    }
}