<?php

App::uses('AppModel', 'Model');

class LogArppuByDay extends AppModel {

	public $useTable = 'log_arppu_by_day';

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
        'game_id'   => array('type' => 'value'),
        'fromTime'  => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'LogArppuByDay.day >= '),
        'toTime'    => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'LogArppuByDay.day <= '),
    );
}