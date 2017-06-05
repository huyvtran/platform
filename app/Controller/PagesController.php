<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {

	public $name = 'Pages';
	
	public $uses = array('Game', 'Website');

	public $cacheAction = array(
		'home' => '+30 minutes',
		'landing' => '+30 minutes'
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
//        $n = "<br/>";
//        $time = 1496422800;
//        echo $time . $n;
//        echo date('Y-m-d H:i:s', $time) . $n;
//
//        $time = 1496454501;
//        echo $time . $n;
//        echo date('Y-m-d H:i:s', $time) . $n;
//        die;
		$this->redirect(array('controller' => 'Administrators', 'action' => 'index', 'admin' => true));
	}



	public function landing()
	{
		$time = time();
		debug($time);
		debug(date('Y-m-d H:i:s', $time));
		die;
	}
	
}
