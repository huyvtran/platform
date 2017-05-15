<?php

App::uses('AppModel', 'Model');

class AccessToken extends AppModel {

	public $useTable = 'access_token';
	
	public $belongsTo = array('User');

	public function generateToken($appKey, $userId)
	{
		$token = $this->find('first', array(
			'conditions' => array(
				'user_id' => $userId,
				'app' => $appKey
			)
		));

		if (empty($token)) {
			$token = $this->_randStr();
			$arrayTemp = array(
				'AccessToken' => array(
					'user_id'	=> $userId,
					'app' 	=> $appKey,
					'token' 	=> $token,
					'type' 		=> 'request',
					'created' 	=> time(),
					'expired' 	=> time() + 999999999
				)
			);
			
			$this->create();
			if (!$this->save($arrayTemp)) {
				CakeLog::error('Can not save token ' . print_r($this->validationErrors, true), 'user');
			}
			$token = $this->read();
		}
		return $token;
	}
	
	private function _randStr() {

		return strtoupper(Security::hash(microtime() . rand(0, 10)));
	}
	
	function paginateCount($conditions = array(), $recursive = 0, $extra = array())
	{
		return 1000;
	}	
}

?>