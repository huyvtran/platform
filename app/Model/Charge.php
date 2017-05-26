<?php

App::uses('AppModel', 'Model');

class Charge extends AppModel {

	public $useTable = 'charges';

	public $belongsTo = array(
		'User', 'Game'
	);
}
