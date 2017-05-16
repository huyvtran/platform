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
		$this->Common->setLanguage();
		$this->Common->setTheme();
		$this->layout = 'default';

        $this->loadModel('Website');
		$this->Website->contain();
		
		$categories = $this->Website->Category->find('all', array(
			'conditions' => array(
				'Category.type' => array('Help', 'Article'),
				'Category.website_id' => $this->Common->currentWebsite('id')
			),
			'contain' => array(
				'Article' 
			),
			'order' => array('Category.lft' => 'ASC')
		));
		foreach ($categories as $category) {
			foreach($category['Article'] as $article) {
				if (strtolower($category['Category']['type']) == 'help') {
					$helps[] = $article;
				} else {
					$articles[] = $article;
				}	
			}
		}
		$game = $this->Game->findById($this->Common->currentGame('id'));
		$this->set(compact('categories', 'articles', 'game'));
	}



	public function landing()
	{
		$this->Common->setLanguage();
		$this->Common->setTheme();
		$this->layout = 'blank';
	}
	
}
