<?php

class ProblemFixture extends CakeTestFixture {

	public $import = array('model' => 'Problem');

	public function __construct()
	{
		parent::__construct();
		$this->records = array(
			array(
				'id' => 1,
				'game_id' => '8',
				'user_id' => '1234',
				'type' => 'Dashboard',
				'description' => 'Test description',
				'created' => '2017-02-17 15:22:30',
				'modified' => '2017-02-17 15:22:30',
				'log_login_id' => 3702,
				'log_enter_game' => 0,
				'mol_username' => 'Test',
				'card_type' => 'viettel',
				'card_cost' => 50000,
				'card_code' => '1234567890',
				'card_serial' => '1234567890',
				'email' => 'abc@gmail.com',
				'phone' => '04113',
				'character' => 'Test',
				'server' => 'S1',
				'note' => 'abc',
				'status' => 1,
				'assign_user' => 'trung',	
			)
		);
	}
}
