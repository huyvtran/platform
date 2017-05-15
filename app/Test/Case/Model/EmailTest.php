<?php

App::uses("EmailMarketing", "Model");
App::uses('AppCakeTestCase', 'Testsuite');

class EmailTest extends AppCakeTestCase {

	public $fixtures = array(
		 'email_marketing', 'game'
	);

	public function setUp() {
		parent::setUp();
		$this->Email = ClassRegistry::init('Email');
		$this->EmailMarketing = ClassRegistry::init('EmailMarketing');
	}


	public function tearDown() {
		unset($this->Email);
		parent::tearDown();
	}

	public function testGetListEmails()
	{
		$email = $this->EmailMarketing->findById(1);

		$result = $this->Email->getListEmails($email['EmailMarketing']['data'], null, false);		
	}
	
	public function testProcessConditions()
	{

		$email = $this->EmailMarketing->findById(2);

		$result = $this->Email->__processConditions($email['EmailMarketing']['data'], null, false);

		ksort($result);
		$result = array_values($result);
		
		$assertResults = array(
			'email_verified' => 1,
        	array(                    
                'User.last_action >= ' => date('Y-m-d 00:00:00', strtotime('-2 months')),
                'User.last_action <= ' => date('Y-m-d 23:59:59', strtotime('-1 months')),
                'country_code' => array('Vietnam','Thailand')
        	),
        	(object) array(
                'type' => 'expression',                                                     
                'value' => " `User`.`id` IN (SELECT user_id FROM `platform_test`.`moborders` AS `Moborder`   WHERE app_key = ('108heroes')   ) "
        	),
        	(object) array(
                'type' => 'expression',                                                        
                'value' => " `User`.`id` IN (SELECT user_id FROM `platform_test`.`moborders` AS `Moborder`   WHERE app_key = ('108heroes')  GROUP BY user_id HAVING SUM(platform_price) > 500  ) "
        	),
        	(object) array(
                'type' => 'expression',
                'value' => " `User`.`id` IN (SELECT user_id FROM `platform_test`.`accounts` AS `Account`   WHERE game_id = (1)   )"  
        	),
        	'NOT' => array(                                                                          
                'email LIKE' => '%haitacmobi%',                                                                     
                'email IS NULL'                                                      
        	)
    	);
		ksort($assertResults);

		$assertResults = array_values($assertResults);

		$this->assertEquals($result, $assertResults);                                                          
	}
}
