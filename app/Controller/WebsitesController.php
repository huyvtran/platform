<?php

App::uses('AppController', 'Controller');

class WebsitesController extends AppController {

	public $components = array(
		'Security' => array('csrfExpires' => '+180 minutes')
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'default_bootstrap';
	}

	public function admin_lock($id)
	{
		
		if ($state = $this->Website->toggle($id, 'lock')) {
			$this->Session->setFlash('You just has been locked website successful', 'success');	
		} else {
			$this->Session->setFlash('You just has been unlocked website successful');	
		}
		$this->redirect($this->referer());
	}

	public function admin_setsession($websiteId = null)
	{
		if (strpos($this->Session->read('Referer'), '/admin/websites/setsession') !== false) {
			$this->Session->delete('Referer');
		}

		if ($this->request->is('post')) {
			$website = $this->Website->findById($websiteId);
			$this->Session->write('Admin.website', $website['Website']);
			$this->Cookie->write('Admin.website', $website['Website']);
			# redirect if session has referrer
			if ($this->Session->read('Referer')) {
				$this->Common->redirect();
			}
		}
		
		$this->loadModel('Permission');
		$role = $this->Auth->user('role');
		if (!in_array($role, array('Admin', 'Developer'))) {
			$ids = $this->Permission->getRightIds('Website', $this->Auth->user('id'));
			$websites = $this->Website->find('all', array(
				'conditions' => array('Website.id' => $ids),
				'order' => array('Website.id' => 'DESC'),
				'recursive' => -1
			));
		} else {
			$websites = $this->Website->find('all', array(
				'order' => array('Website.id' => 'DESC'),
			));
		}

		$this->set(compact('websites'));
	}
}
