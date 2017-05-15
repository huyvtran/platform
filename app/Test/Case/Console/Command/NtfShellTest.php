<?php

App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('NtfShell', 'Console/Command');
App::uses('ApnsTask', 'Console/Command/Task');
App::uses('GcmTask', 'Console/Command/Task');
App::uses('MpnsTask', 'Console/Command/Task');
App::uses('AppCakeTestCase', 'Testsuite');

class NtfShellTest extends AppCakeTestCase {
	public $fixtures = array(
		'notification'
	);

	public function setUp() {
		parent::setUp();

		$out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$in = $this->getMock('ConsoleInput', array(), array(), '', false);
		$this->Shell = $this->getMock(
			'NtfShell',
			array('in', 'out', 'hr', 'error', 'err', '_stop'),
			array($out, $out, $in)
		);

		$this->Shell->Apns = $this->getMock('ApnsTask',
			array('in', 'out', 'hr', 'error', 'err', '_stop'),
			array($out, $out, $in)
		);
		$this->Shell->Gcm = $this->getMock('GcmTask',
			array('in', 'out', 'hr', 'error', 'err', '_stop'),
			array($out, $out, $in)
		);
		$this->Shell->Mpns = $this->getMock('MpnsTask',
			array('in', 'out', 'hr', 'error', 'err', '_stop'),
			array($out, $out, $in)
		);

		$this->Shell->Notification = ClassRegistry::init('Notification');
		$this->Shell->Game = ClassRegistry::init('Game');
	}



	public function tearDown() {
		parent::tearDown();
		unset($this->Shell->Apns);
		unset($this->Shell->Gcm);
		unset($this->Shell->Mpns);
		unset($this->Shell->Notification);
		unset($this->Shell->Game);
		unset($this->Shell);
	}

	public function testpushToQueue(){
		$this->Shell->Notification = $this->getMockForModel('Notification', array('find'));

		$this->Shell->Notification->expects($this->at(0))
		->method('find')
			->will($this->returnValue(array()));

		$result = $this->Shell->pushToQueue();
		$this->assertNull($result);
	}

	public function testpush(){
		$result = $this->Shell->push();
		$this->assertNull($result);
	}

	public function testGetFeedbackApns(){
		$result = $this->Shell->getFeedbackApns();
		$this->assertNull($result);
	}

	public function testPushScheduler(){
		$result = $this->Shell->pushScheduler();
		$this->assertNull($result);
	}

	public function testPushTokenToRedis(){
		$result = $this->Shell->pushTokenToRedis();
		$this->assertNull($result);
	}

	public function testRemoveTokenRedis(){
		$result = $this->Shell->removeTokenRedis();
		$this->assertNull($result);
	}

	public function testPushToUser(){
		$result = $this->Shell->pushToUser();
		$this->assertNull($result);
	}

	public function testPushToUserAllDevice(){
		$result = $this->Shell->pushToUserAllDevice();
		$this->assertNull($result);
	}
}