<?php

App::uses('AppControllerTestCase', 'Testsuite');

class UsersControllerTest extends AppControllerTestCase {

	public $fixtures = array(
		 'user'
	);

	public function testIndex()
	{
		$result = $this->testAction('/admin/users/index', array(
			'return' => 'contents'
		));
	}

	public function testReveiceAccessToken()
	{
		$Users = $this->generate('Users', array(
			'methods' => array(
				'__findExistByFbInfo',
				'__getFBUser'
			)
		));

		$Users->expects($this->once())
			->method('__getFBUser')
			->will($this->returnValue(array('hello')));

		$result = $this->testAction('/users/reveiceAccessToken', array(
			'data' => array(
				'access_token' => 'ThisIsAFacebookToken'
			),
			'return' => 'view'
		));
		
	}
}