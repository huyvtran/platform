<?php

App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('LogShell', 'Console/Command');
App::uses('AppCakeTestCase', 'Testsuite');
App::import('Lib', 'RedisQueue');

class LogShellTest extends AppCakeTestCase {

	public $fixtures = array(
		'User'
	);

	public function setUp() {
		parent::setUp();

		$out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$in = $this->getMock('ConsoleInput', array(), array(), '', false);
		$this->Shell = $this->getMock(
			'LogShell',
			array('in', 'out', 'hr', 'error', 'err', '_stop'),
			array($out, $out, $in)
		);
	}


	public function tearDown() {
		parent::tearDown();
	}

	public function testSave()
	{
		$_SERVER['MESSAGEBIRD_KEY'] = 'test_agazWZPJ6MjisOzJMHzb7GlM3'; # test key
		$_SERVER['TWILIO_SID'] = 'AC62df56a34ccac077378a02dacc36edf0'; # test key
		$_SERVER['TWILIO_TOKEN'] = '05e74ab6bab4a6ec2e9a64b43398e01a'; # test key


		$Redis = new RedisQueue();
		$Redis->lPush(array(
			'type' => 'SendSms',
				'data' => array(
		    		'To' => '+841652049678',
		    		'Body' => 'SMS testSave'
				)
		));
		$this->Shell->save();
	}

	public function test__typeSendSms()
	{
		$_SERVER['MESSAGEBIRD_KEY'] = 'test_agazWZPJ6MjisOzJMHzb7GlM3'; # test key
		$_SERVER['TWILIO_SID'] = 'AC62df56a34ccac077378a02dacc36edf0'; # test key
		$_SERVER['TWILIO_TOKEN'] = '05e74ab6bab4a6ec2e9a64b43398e01a'; # test key

		# example Test Private method
		$class = new ReflectionClass('LogShell');
		$method = $class->getMethod('__typeSendSms');
		$method->setAccessible(true);

		
		$result = $method->invokeArgs($this->Shell, array(
			'data' => array(
				'data' => array(
	    			'To' => '+841652049678',
	    			'Body' => 'SMS TypeSendSms'
	    		)
			)
		));
		$this->assertInstanceOf('MessageBird\Objects\Message', $result);
	}

	public function test__sendSmsByTwillo()
	{
		$_SERVER['MESSAGEBIRD_KEY'] = 'test_agazWZPJ6MjisOzJMHzb7GlM3'; # test key
		$_SERVER['TWILIO_SID'] = 'AC62df56a34ccac077378a02dacc36edf0'; # test key
		$_SERVER['TWILIO_TOKEN'] = '05e74ab6bab4a6ec2e9a64b43398e01a'; # test key

		# example Test Private method
		$class = new ReflectionClass('LogShell');
		$method = $class->getMethod('__sendSmsByTwillo');
		$method->setAccessible(true);

		
		$result = $method->invokeArgs($this->Shell, array(
			'to' => '+841652049678',
			'options' => array(
				'from' => '+15005550006',
				'body' => 'SMS test__sendSmsByTwillo'
			)
		));
		$this->assertTrue(is_string($result));
	}	
}