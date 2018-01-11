<?php

App::uses('AppModel', 'Model');

class Bonus extends AppModel {
    public $actsAs = array(
        'Search.Searchable',
        'Utils.Publishable' => array(
            'field' => 'status'
        ),
    );

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
        ),
        'price' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty'
            )
        )
	);

    public $belongsTo = array( 'Game' , 'User');
}
