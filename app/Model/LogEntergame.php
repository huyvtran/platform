<?php

App::uses('AppModel', 'Model');

/**
 * Lưu record chứa thông tin nhân vật, server của users tại thời điểm sau khi vào game
 */
class LogEntergame extends AppModel {

	public $validate = array(
		'user_id' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		),
		'game_id' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		)
	);
}