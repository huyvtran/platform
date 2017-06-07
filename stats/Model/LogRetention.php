<?php

App::uses('AppModel', 'Model');

class LogRetention extends AppModel {

    public $useTable = 'log_retention_by_day';
	public $belongsTo = array('Game');
}