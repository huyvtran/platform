<?php

App::uses('AppModel', 'Model');

class Payment extends AppModel {

	public $useTable = 'payments';

	const TYPE_NETWORK_VIETTEL          = 'VTT';
	const TYPE_NETWORK_VINAPHONE        = 'VNP';
	const TYPE_NETWORK_MOBIFONE         = 'VMS';

	const CHANEL_VIPPAY	= 1;

	public $belongsTo = array(
		'User', 'Game'
	);

	public $hasOne = array( 'WaitingPayment' );
}
