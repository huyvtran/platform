<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');
App::uses('Security', 'Utility');

class UsersController extends AppController {

	public $uses = array('User');
	public $cacheAction = array(
	);
	
	public $components = array(
		'Auth',
		'Session',
		'Cookie',
		'Paginator',
		'Security'	 => array(
			'csrfUseOnce' => false,
			'csrfExpires' => '+30 minutes'
		),
        'Search.Prg'
	);

	public $presetVars = array(
		array('field' => 'search', 'type' => 'value'),
		array('field' => 'username', 'type' => 'value'),
		array('field' => 'email', 'type' => 'value')
	);
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow(array('login', 'logout'));
	}

	public function login()
	{
		if ($this->request->query('code')) {
			$HttpSocket = new HttpSocket;
			$this->loadModel('Game');
			$this->Game->recursive = -1;
			$game = $this->Game->findByTitle('stats');
			$response = $HttpSocket->get(
				str_replace(
					array('localhost:8088', 'localhost'),
					'127.0.0.1',
					Configure::read('OAuth.MYAPP.reveice')
				), array(
				'code' => $this->request->query('code'),
				'secret' => $game['Game']['secret_key']
			));

			if ($response->code != 200) {
				throw new InternalErrorException('Internal error, please report admin');
			}
			$response = json_decode($response->body, true);

			$this->Auth->login($response['User']);
			$this->Cookie->write('User', array(
				'username' => $response['User']['username'], 'email' => $response['User']['email']
			));

            $cookie = $this->Cookie->read('User');
            debug($cookie);die;
			$this->Session->setFlash('You has been logged in successfully', 'success');
			$this->Session->delete('Message.auth');
			$this->redirect($this->request->query('redirect'));
		} else {
			$this->redirect(
					Configure::read('OAuth.MYAPP.request')
				.	'?app=stats&continue='
				.	Router::url(array('controller' => 'users', 'action' => 'login', '?' => array('redirect' => urlencode(Router::url('/', true)))), true)
			);
		}
		$this->autoRender = false;
	}

	public function logout()
	{
		if ($this->Auth->loggedIn()) {
			$this->Cookie->destroy();
			$this->Session->destroy();
			$this->Session->setFlash(sprintf('%s you have successfully logged out', $this->Auth->user('username')), 'success');
			$this->redirect($this->referer('/', true));
		}
		$this->redirect($this->referer('/', true));
	}
}
