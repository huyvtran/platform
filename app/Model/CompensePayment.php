<?php

App::uses('AppModel', 'Model');

class CompensePayment extends AppModel {
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
        'card_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty'
            )
        ),
        'card_serial' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty'
            )
        ),
        'price' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty'
            )
        ),
        'type' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty'
            )
        ),
        'chanel' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty'
            )
        )
	);

    public $belongsTo = array( 'Game' , 'User');

    public $filterArgs = array(
        'game_id' => array('type' => 'value'),
        'status' => array('type' => 'value'),
        'username' => array('type' => 'value', 'field' => 'user_id'),
    );
}
