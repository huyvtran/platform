<?php

App::uses('ModelBehavior', 'Model');

class ReportBehavior extends ModelBehavior
{
	/**
	 * @var array
	 * need to escape %, because it will be passed to sprintf
	 */

	static protected $_groupBy = array(
		'daily'     => "Day.date",
		'monthly'   => "Day.month, Day.year",
		'quarterly' => "Day.quarter, Day.year"
	);

	protected $_model;
	protected $_from;
	protected $_to;
	protected $_type;
	protected $_data = null;

	public function getChartData(Model $model, $campaignId, $gameId, $type, DateTime $from, DateTime $to)
	{
		// data is already sanitized in controller
		$this->_model = $model;
		$this->_from  = $from;
		$this->_to    = $to;
		$this->_type  = $type;

		$this->_model->virtualFields['main_value'] = 0;
		$this->_model->virtualFields['group_by']   = '';

		$this->_model->bindModel(
			array(
				'hasOne' => array(
					'Day'
				)
			)
		);
		$this->_model->Day->populateCalendarData();

		// add game filter if needed
		$gameFilter = '';
		if (!empty($gameId)) {
			$gameFilter = 'game_id = ' . $gameId;
		}

		$this->_data = $this->_model->Day->find(
			'all',
			array(
				'fields' => array(
					"COUNT({$model->name}.id) as {$model->name}__main_value",
					self::$_groupBy[$type] . " as {$model->name}__group_by",
				),

				'conditions' => array(
					"Day.date BETWEEN ? AND ?"  => array($from->format('Y-m-d'), $to->format('Y-m-d')),
				),

				'joins' => array(
					array(
						'table'      => $model->table,
						'alias'      => $model->name,
						'type'       => 'LEFT',
						'conditions' => array(
							"DATE({$model->name}.created) = Day.date",
							'campaign_id' => $campaignId,
							$gameFilter
						)
					)
				),

				'group' => array(
					self::$_groupBy[$type]
				),

				'order'     => array(self::$_groupBy[$type] . ' ASC'),
				'recursive' => -1
			)
		);

		return $this->_extractChartData();
	}

	protected function _extractChartData()
	{
		if ($this->_data === null) {
			throw new CakeBaseException('No data is loaded');
		}

		$parsed = array();

		// data first
		foreach ($this->_data as $row) {
			$modelName = $this->_model->name;

			$parsed['data'][] = (int)$row[$modelName]['main_value'];
		}

		return $parsed;
	}

}
