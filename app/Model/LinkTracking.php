<?php

App::uses('AppModel', 'Model');
App::uses('EmailMarketing', 'Model');

class LinkTracking extends AppModel {
	public function unhashStr($str)
	{		
		return EmailMarketing::unhashStr($str);
	}
}

?>