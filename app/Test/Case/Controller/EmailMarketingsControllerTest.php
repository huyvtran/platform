<?php

App::uses('AppControllerTestCase', 'Testsuite');

class EmailMarketingsControllerTest extends AppControllerTestCase {

	public $fixtures = array(
		'email_marketing'
	);

	public function testIndex()
	{
		$result = $this->testAction('/admin/EmailMarketings/index', array(
			'return' => 'contents',
		));
	}

	public function testPublish()
	{
		$result = $this->testAction('/admin/EmailMarketings/publish/1', array(
			'return' => 'contents',
		));
	}

	public function testUnpublish()
	{
		$result = $this->testAction('/admin/EmailMarketings/unpublish/1', array(
			'return' => 'contents',
		));
	}

	public function testView()
	{
		$result = $this->testAction('/admin/EmailMarketings/view/1', array(
			'return' => 'contents',
		));
	}

	public function testAdd()
	{
		$data = array(
			'EmailMarketing' => array(
				'title' => 'Test Email',
				'type' => '0',
				'game_id' => '',
				'ad_for_game_id' => '8',
				'data' => array(
					'countries' => '',
					'game_id' => array(1, 8),
					'field' => '',
					'from_time' => '',
					'to_time' => '',
					'vip' => '',
					'paid_users' => '0',
					'not_paid_users' => '0',
					'paid_more_than' => '',
					'segment' => '',
					'email_marketing_group' => '',
					'email_marketing_id' => '',
					'duplicate_email' => '0',
					'game_id_duplicate' => '',
					'addresses_duplicate' => '',
					'giftcodes' => ''
				),
				'giftcodefile' => array(
					'name' => '',
					'type' => '',
					'tmp_name' => '',
					'error' => '4',
					'size' => '0'
				)
			)
		);
		$result = $this->testAction('/admin/EmailMarketings/add', array(
			'data' => $data,
			'method' => 'post',
			'return' => 'view'
		));
	}

	public function setUp() {
		parent::setUp();
	}


	public function tearDown() {
		parent::tearDown();
	}

}