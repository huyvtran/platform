<?php

App::uses('AppModel', 'Model');

class Transaction extends AppModel {

	public $useTable = 'transactions';

	const TYPE_PAY    	= 1;
	const TYPE_SPEND 	= 2;
}
