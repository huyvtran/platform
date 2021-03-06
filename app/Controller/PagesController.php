<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {

	public $name = 'Pages';
	
	public $uses = array('Game', 'Website');

	public $cacheAction = array(
		'home' => '+30 minutes'
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow();
		$this->layout = 'default_bootstrap';
		if (isset($this->request->params['pass'][0]) && method_exists($this, $this->request->params['pass'][0])) {
			$this->setAction($this->request->params['pass'][0]);
		}
	}

	public function home()
	{
		$this->redirect(array('controller' => 'Administrators', 'action' => 'index', 'admin' => true));
	}
}
