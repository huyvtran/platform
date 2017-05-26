<?php

App::uses('AppModel', 'Model');

class Transaction extends AppModel {

	public $useTable = 'transactions';

	const TYPE_PAY    	= 1; // nạp tiền vào tài khoản
	const TYPE_SPEND 	= 2; // rút tiên từ tài khoản chuyển vào game

	public $belongsTo = array(
		'User', 'Game'
	);
}
