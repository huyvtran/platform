<?php
/**
 * AccessTokenFixture
 *
 */
class AccessTokenFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'access_token';

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'AccessToken');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'app_key' => '108heroes',
			'user_id' => 1,
			'token' => 'thisistoken',
			'type' => 'request',
			'created' => 1378132933,
			'expired' => 2378132933
		),
		array(
			'id' => 2,
			'app_key' => 'Lorem ipsum dolor sit amet',
			'user_id' => 2,
			'token' => 'Lorem ipsum dolor sit amet',
			'type' => 'Lorem ipsum dolor sit amet',
			'created' => 2,
			'expired' => 2
		),
	);

}
