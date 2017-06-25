<?php

App::uses('AppModel', 'Model');
App::uses('EmailMarketing', 'Model');

class LinkTracking extends AppModel {
	public function unhashStr($str)
	{
		$EmailMarketing = ClassRegistry::init('EmailMarketing');
		return $EmailMarketing->unhashStr($str);
	}
}

?>