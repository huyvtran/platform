<?php

App::uses('Model', 'Model');

class Account extends AppModel {

	//public $actsAs = array('Search.Searchable');

	//public $hasMany = array('User');
	
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
//
//	public function __get($name) {
//		if (property_exists($this, $name))
//			return $this->$name;
//
//		switch ($name) {
//			case 'created':
//				return "2013-07-29 00:00:00";
//			default:
//				throw new Exception("Attempt to get a non-existing property: $name");
//				break;
//		}
//	}
//
//
//	public function getAndroidAccountTotal($gameIds = null)
//	{
//		$count = Cache::read('android_account_total');
//		if ($count === false) {
//			$Game = ClassRegistry::init('Game');
//			$count = $this->find('count', array(
//				'conditions' => array(
//					'Account.game_id' => $Game->find('list', array(
//						'conditions' => array('os' => 'android'),
//						'fields' => array('id', 'id')
//					))
//				),
//				'recursive' => -1
//			));
//			Cache::write('android_account_total', $count);
//		}
//		return $count;
//	}
//
//	public function getIosAccountTotal($gameId = null)
//	{
//		$count = Cache::read('ios_account_total');
//		if ($count === false) {
//			$Game = ClassRegistry::init('Game');
//			$count = $this->find('count', array(
//				'conditions' => array(
//					'Account.game_id' => $Game->find('list', array(
//						'conditions' => array('os' => 'ios'),
//						'fields' => array('id', 'id')
//					))
//				),
//				'recursive' => -1
//			));
//			Cache::write('ios_account_total', $count);
//		}
//		return $count;
//	}

}
