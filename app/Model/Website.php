<?php
App::uses('AppModel', 'Model');

class Website extends AppModel {

	public $validationDomain = 'not_translate';

	public $actsAs = array(
		'Utils.Toggleable' => array(
			'fields' => array('lock' => array(0, 1)),
			'checkRecord' => false
		)
	);

	public $hasMany = array('Category', 'Game');

	public $validate = array(
		'game_id' => array('rule' => 'notEmpty'),
		'url' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty'
			),
			'unique' => array(
				'rule' => 'isUnique'
			)
		),
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty'
			),
			'unique' => array(
				'rule' => 'isUnique'
			)
		)
	);	

	public function afterSave($created)
	{
		# Clear detect request to which game, or website in CommonComponent
		Cache::clear(false, 'info');
		clearCachefile('config_language_websites', '', '');
	}
}