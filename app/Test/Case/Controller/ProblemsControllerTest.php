<?php

App::uses('AppControllerTestCase', 'Testsuite');

class ProblemsControllerTest extends AppControllerTestCase {

	public $fixtures = array(
		 'problem'
	);

	public function setUp() {
		parent::setUp();
	}


	public function tearDown() {
		parent::tearDown();
	}

	public function testIndex2()
	{
		$result = $this->testAction('/admin/Problems/index2/mark:1/from_time:1487437200/chanel:Email/assign_user:3259052/types:account/vip:Platinum/mailsend:1/search:abc/game_id:8/assign_users:1234/ass_user:1234/created:3', array(
			'return' => 'contents'
		));
	}

	// public function testUploadImageIssue()
	// {
	// 	$ProblemDashboard = $this->generate('Problems', array(
	// 		'components' => array('Common'),
	// 	));
	// 	$data = array(
	// 		'ProblemDashboard' => array(
	// 			'0' => array(
	// 				'tmp_name' => 'tesst',
	// 				'name' => 'tesst',
	// 			)	
	// 		)
	// 	);
	// 	$result = $this->testAction('/Problems/uploadImageIssue.json?id=1', array(
	// 		'data' => $data,
	// 		'method' => 'post',
	// 		'return' => 'view'
	// 	));
	// 	$result = json_decode($result, true);
	// 	$test = array(
	// 		'code' => 1,
	// 		'status' => true,
	// 		'message' => 'OK'
	// 	);
	// 	$this->assertEquals($test, $result);
	// }
 
 	public function testListreport2()
 	{
 		$result = $this->testAction('/Problems/listreport2', array(
			'return' => 'contents'
		));
 	}

 	public function testReport_4()
 	{
 		$data = array(
			'Problem' => array(
				'id' => 1,
				'game_id' => '8',
				'user_id' => '1234',
				'type' => 'card',
				'description' => 'Test description',
				'created' => '2017-02-17 15:22:30',
				'modified' => '2017-02-17 15:22:30',
				'log_login_id' => 3702,
				'log_enter_game' => 1234,
				'card_type' => 'true_money',
				'card_cost' => 50000,
				'card_code' => '1234567890',
				'card_serial' => '1234567890',
				'phone' => '0986277218',
				'character' => 'Test',
				'server' => 'S1',
				'status' => 0,
				'chanel' => 'Dashboard',
				'title' => 'Test',
			)
		);
		$result = $this->testAction('/Problems/report_4', array(
			'data' => $data,
			'method' => 'post',
			'return' => 'contents'
		));
 	}

 	public function testReport_submit()
 	{
 		$result = $this->testAction('/Problems/report_submit', array(
			'return' => 'contents'
		));
 	}

 	public function testDetail_issue()
    {
	    $data = array(
		    'ProblemDashboard' => array(
			    'id' => 1,
			    'problem_id' => 1,
			    'sender_name' => 'abc@gmail.com',
			    'type' => 0,
			    'body' => 'abcdef',
		    )
	    );
	    $result = $this->testAction('/Problems/detail_issue/1', array(
		    'data' => $data,
		    'method' => 'post',
		    'return' => 'contents'
	    ));
	    $_ENV['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
	    $result_ajax = $this->testAction('/Problems/detail_issue/1', array(
		    'data' => array('start' => 5),
		    'method' => 'post',
		    'return' => 'vars'
	    ));
    }

	public function testCheck_image()
	{
		$_ENV['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result_ajax = $this->testAction('/Problems/check_image', array(
			'data' => array('id' => 1, 'type' => 1),
			'method' => 'post',
			'return' => 'vars'
		));
	}
}