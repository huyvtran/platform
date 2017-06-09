<?php

App::uses('AppModel', 'Model');

/**
 * Lưu record chứa thông tin thiết bị của users tại thời điểm login
 * có thể sử dụng để thống kê thông tin thiết bị sử dụng.
 */
class LogArpuByDay extends AppModel {

	public $useTable = 'log_arpu_by_day';

    public $validate = array(
        'value' => array(
            'rule'       => 'notEmpty',
            'required'   => true,
            'allowEmpty' => false
        ),
        'game_id' => array(
            'rule'       => 'notEmpty',
            'required'   => true,
            'allowEmpty' => false
        ),
        'day' => array(
            'rule'       => 'notEmpty',
            'required'   => true,
            'allowEmpty' => false
        )
    );

    public $actsAs = array('Search.Searchable');

    public $belongsTo = array('Game');

    public $filterArgs = array(
        'game_id' => array('type' => 'value'),
        'fromTime' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'LogArpuByDay.day >= '),
        'toTime' => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'LogArpuByDay.day <= '),
    );
}