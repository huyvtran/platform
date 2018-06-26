<?php

App::uses('AppModel', 'Model');

/**
 * Lưu record chứa thông tin thiết bị của users tại thời điểm login
 * có thể sử dụng để thống kê thông tin thiết bị sử dụng.
 */
class LogLogin extends AppModel
{

    public $validate = array(
        'user_id' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false
        ),
        'game_id' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false
        )
    );

    function paginateCount($conditions = array(), $recursive = 0, $extra = array())
    {
        if( empty($conditions['LogLogin.ip']) ) return 100;
    }
}