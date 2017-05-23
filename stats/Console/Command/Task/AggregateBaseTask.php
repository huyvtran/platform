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
}