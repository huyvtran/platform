<?php

App::uses('AppModel', 'Model');

class Genre extends AppModel {

	public $validationDomain = 'not_translate';
	
	public $displayField = 'title';

	public $actsAs = array(
		'Utils.Sluggable' => array(
			'separator' => '-',
			'update'	=> true
		)
	);

	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
			)
		),
	);

	public $hasAndBelongsToMany = array(
		'Game'
	);

}
