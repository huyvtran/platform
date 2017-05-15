<?php

App::uses('EmailMarketing', 'Model');

class EmailMarketingFixture extends CakeTestFixture {

	public $import = array('model' => 'EmailMarketing');

	public function __construct()
	{
		$this->records = array(
			array(
				'id' => 1, # Test send Marketing email
				'title' => 'Test send email marketing',
				'type' => EmailMarketing::TYPE_ALL,
				'body' => '<div>Hello</div>',
				'parsed_body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd"><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head>
						<body>Hello @friendlyName 
						your giftcode: @giftcode 
						Cảm ơn bạn đã đọc e-mail, nếu bạn không muốn tiếp tục nhận vui lòng click @unsubscribe[vào đây] 
						</body>
					</html>',
				'created' => date('Y-m-d H:i:s', strtotime('-12 hours')),
				'modified' => date('Y-m-d H:i:s', strtotime('-12 hours')),
				'published_date' => date('Y-m-d H:i:s', strtotime('-11 hours')),
				'user_id' => 1,
				'game_id' => 1,
				'layout' => 'blank',
				'file' => null,
				'status' => 1,
				'data' => array(
					'countries' => array(),
					'game_id' => array(1),
					'field' => '',
					'from_time' => '',
					'to_time' => '',
					'vip' => '',
					'paid_users' => 0,
					'not_paid_users' => 0,
					'paid_more_than' => 0,
					'segment' => '',
					'email_marketing_group' => '',
					'email_marketing_id' => '',
					'duplicate_email' => 0,
					'game_id_duplicate' => '',
					'addresses_duplicate' => '',
					'giftcodes' => ''
				),
			),	
			array(
				'id' => 2, # use for Model/EmailTest
				'title' => 'Server opens today! Join now and claim a Legandary Pokemon for Free',
				'type' => EmailMarketing::TYPE_ALL,
				'body' => '<div>Hello</div>',
				'parsed_body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd"><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head><body>Hello</body></html>',
				'created' => date('Y-m-d H:i:s', strtotime('-12 hours')),
				'modified' => date('Y-m-d H:i:s', strtotime('-12 hours')),
				'published_date' => date('Y-m-d H:i:s', strtotime('-11 hours')),
				'user_id' => 1,
				'game_id' => 1,
				'layout' => 'blank',
				'file' => null,
				'status' => 1,
				'data' => array(
					'countries' => array('Vietnam', 'Thailand'),
					'game_id' => array(1),
					'field' => 'last_action',
					'from_time' => date('Y-m-d H:i:s', strtotime('-2 months')),
					'to_time' => date('Y-m-d H:i:s', strtotime('-1 months')),
					'vip' => '',
					'paid_users' => 1,
					'not_paid_users' => 0,
					'paid_more_than' => 500,
					'segment' => '',
					'email_marketing_group' => '',
					'email_marketing_id' => '',
					'duplicate_email' => 0,
					'game_id_duplicate' => '',
					'addresses_duplicate' => '',
					'giftcodes' => ''
				),
			),
			array(
				'id' => 3, # Test send Giftcode email
				'title' => 'Test send giftcode',
				'type' => EmailMarketing::TYPE_GIFTCODE,
				'body' => '<div>Hello</div>',
				'parsed_body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd"><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head>
						<body>Hello @friendlyName 
						your giftcode: @giftcode 
						Cảm ơn bạn đã đọc e-mail, nếu bạn không muốn tiếp tục nhận vui lòng click @unsubscribe[vào đây] 
						</body>
					</html>',
				'created' => date('Y-m-d H:i:s', strtotime('-12 hours')),
				'modified' => date('Y-m-d H:i:s', strtotime('-12 hours')),
				'published_date' => date('Y-m-d H:i:s', strtotime('-11 hours')),
				'user_id' => 1,
				'game_id' => 1,
				'layout' => 'blank',
				'file' => null,
				'status' => 1,
				'data' => array(
					'countries' => array(),
					'game_id' => array(1),
					'field' => '',
					'from_time' => '',
					'to_time' => '',
					'vip' => '',
					'paid_users' => 0,
					'not_paid_users' => 0,
					'paid_more_than' => 0,
					'segment' => '',
					'email_marketing_group' => '',
					'email_marketing_id' => '',
					'duplicate_email' => 0,
					'game_id_duplicate' => '',
					'addresses_duplicate' => '',
					'giftcodes' => 'ABC
					                DEF'
				),
			),				
		);
  
		foreach ($this->records as $key => &$value) {
			$value['data'] = serialize($value['data']);
		}
		parent::__construct();

	}
}
