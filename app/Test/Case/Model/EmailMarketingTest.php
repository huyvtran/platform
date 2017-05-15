<?php

App::uses("EmailMarketing", "Model");
App::uses('AppCakeTestCase', 'Testsuite');

class EmailMarketingTest extends AppCakeTestCase {

	public $fixtures = array(
		 'email_marketing', 'website', 'game'
	);

	public function setUp() {
		parent::setUp();
		$this->EmailMarketing = ClassRegistry::init('EmailMarketing');
	}


	public function tearDown() {
		unset($this->EmailMarketing);
		parent::tearDown();
	}

	public function testSend()
	{
		$result = $this->EmailMarketing->send(1, 'tom@gmail.com', array('@friendlyName' => 'Tom', '@email' => 'tom@gmail.com', '@giftcode' => 'thisisgiftcode'));
		$this->assertRegExp("/.*Hello Tom.*/", $result['message']);
		$this->assertRegExp("/.*thisisgiftcode.*/", $result['message']);
		$this->assertRegExp("/.*\/emailFeedbacks\/unsubscribe.*/", $result['message']);
	}

}
