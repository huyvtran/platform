<?php

App::uses('AppModel', 'Model');

class AuthorizationCode extends AppModel {

	public $validate = array(
		'user_id' => array(
			'rule' => 'notEmpty'
		),
		'code' => array(
			'rule' => 'notEmpty'
		),
		'game_id' => array(
			'rule' => 'notEmpty'
		),
		'expires' => array(
			'rule' => 'notEmpty'
		),
	);
	public $recursive = -1;

	public $belongsTo = array('Game', 'User');

	public function generateCode($appId, $userId, $redirect = null)
	{
		$code = md5($userId . time());
		$result = $this->save(array(
			'game_id' => $appId,
			'user_id' => $userId,
			'code' => $code,
			'expires' => date('Y-m-d H:i:s', 300 + time()),
			'redirect_url' => $redirect
		));
		if (!$result) {
			throw new Exception("Can not to generate code");
		}
		return $code;
	}
	
}

?>