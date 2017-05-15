<?php

class UserFixture extends CakeTestFixture {

	public $import = array('model' => 'User');

	public $records = array(
		array(
			'id' => 1,
			'username' => 'meotimdihia',
			'name' => 'Dien',
			'slug' => 'Vu',
			'password' => 'whatispassword',
			'password_token' => 'Lorem ipsum dolor sit amet',
			'email' => 'meotimdihia@gmail.com',
			'email_verified' => 1,
			'email_token' => 'Lorem ipsum dolor sit amet',
			'email_token_expires' => '2013-10-05 22:04:30',
			'tos' => 1,
			'active' => 1,
			'last_login' => '2013-10-05 22:04:30',
			'last_action' => '2013-10-05 22:04:30',
			'role' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-10-05 22:04:30',
			'modified' => '2013-10-05 22:04:30',
			'facebook_uid' => 'whatisfbid',
			'device_id' => 'Lorem ipsum dolor sit amet',
			'updated' => 1
		),
		array(
			'id' => 2,
			'username' => 'username2',
			'name' => 'Elizabeth olsen',
			'slug' => 'elizabeth-olsen',
			'password' => 'whatispassword2',
			'password_token' => 'Lorem ipsum dolor sit amet',
			'email' => 'abcdef@yahoo.com',
			'email_verified' => 1,
			'email_token' => 'Lorem ipsum dolor sit amet',
			'email_token_expires' => '2013-10-05 22:04:30',
			'tos' => 1,
			'active' => 1,
			'last_login' => '2013-10-05 22:04:30',
			'last_action' => '2013-10-05 22:04:30',
			'role' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-10-05 22:04:30',
			'modified' => '2013-10-05 22:04:30',
			'facebook_uid' => 'whatisfbid',
			'device_id' => 'Lorem ipsum dolor sit amet',
			'updated' => 1
		),		
	);

}
