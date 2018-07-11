<?php

App::uses('AppModel', 'Model');

/**
 * Lưu record chứa thông tin thiết bị của users tại thời điểm login
 * có thể sử dụng để thống kê thông tin thiết bị sử dụng.
 */
class LogInstallByDay extends AppModel {

	public $useTable = 'log_installs_by_day';

    public $belongsTo = array('Game');

    public $actsAs = array(
        'Search.Searchable'
    );

    public $filterArgs = array(
        'game_id' => array('type' => 'value'),
        'fromTime' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'LogInstallByDay.day >= '),
        'toTime' =>  array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'LogInstallByDay.day <= '),
    );

    public function fromTimeCond($data = array())
    {
        return date('Y-m-d', $data['fromTime']);
    }

    public function toTimeCond($data = array())
    {
        return date('Y-m-d', $data['toTime']);
    }

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
}