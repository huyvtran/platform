<?php
App::uses('Shell', 'Console');
App::uses('AppShell', 'Console/Command');

class AppShell extends Shell {
	public function initialize()
	{
		ini_set('memory_limit', '512M');
		parent::initialize();
	}
}
