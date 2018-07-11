<?php

App::uses('AppModel', 'Model');

class LogInstall extends AppModel
{
    public $useTable = 'log_installs';

    public $validate = array(
        'device' => array(
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
}