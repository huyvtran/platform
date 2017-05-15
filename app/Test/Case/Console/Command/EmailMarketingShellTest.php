<?php

App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('EmailMarketingShell', 'Console/Command');
App::uses('SendEmailTask', 'Console/Command/Task');
App::uses('AppCakeTestCase', 'Testsuite');

class EmailMarketingShellTest extends AppCakeTestCase {

	public $fixtures = array(
		 'email_marketing', 'user', 'account', 'game', 'website'
	);

	public function setUp() {
		parent::setUp();
		$out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$in = $this->getMock('ConsoleInput', array(), array(), '', false);
		$this->Shell = $this->getMock(
			'EmailMarketingShell',
			array('in', 'out', 'hr', 'error', 'err', '_stop'),
			array($out, $out, $in)
		);

		$this->Shell->SendEmail = $this->getMock('SendEmailTask',
			array('in', 'out', 'hr', 'error', 'err', '_stop'),
			array($out, $out, $in)
		);
		$this->Shell->SendEmail->EmailMarketing = ClassRegistry::init('EmailMarketing');
		$this->Shell->SendEmail->EmailFeedback = ClassRegistry::init('EmailFeedback');
		$this->Shell->EmailMarketing = ClassRegistry::init('EmailMarketing');
		$this->Shell->Email = ClassRegistry::init('Email');
	}



	public function tearDown() {
		parent::tearDown();
	}

	public function testPushSqsAndSend()
	{
		$this->Shell->SendEmail->EmailFeedback = $this->getMockForModel('EmailFeedback', array('wasBlocked'));
		$this->Shell->SendEmail->EmailFeedback->expects($this->exactly(2)) 
	        				->method('wasBlocked')
	        				->will($this->returnValue(false));
                        
		$this->Shell->pushSqs();
		$this->Shell->send();
	}
}