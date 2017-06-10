<?php
App::uses('Component', 'Controller');

class CommandComponent extends Component {

	public $components = array(
		'Auth', 'Session', 'Common', 'Log'
	);
	
	public function initialize(Controller $controller)
	{
		$this->Controller = $controller;
	}

	public function authen($action = 'login', $return = false, $fixSecurity = false)
	{
		$controller = $this->Controller;
		$app = $controller->request->header('app');
		$controller->loadModel('AccessToken');
		$controller->loadModel('Account');
		
		$userId = $this->Auth->user('id');
		if (!empty($userId)) {
			$token = $controller->AccessToken->generateToken($app, $userId);
		}

		$controller->Account->contain();
		$account = $controller->Account->findByUserIdAndGameId(
			$this->Auth->user('id'),
			$this->Common->currentGame('id')
		);

		if (empty($account)) {
			CakeLog::error('Không tìm thấy account này ' . $this->Auth->user('id') . ' - ' . $this->Common->currentGame('id'));
			$controller->Cookie->destroy();
			$controller->Session->destroy();
			$controller->Session->setFlash(__('Xin lỗi, đã có lỗi xảy ra!'), 'error');
			$controller->redirect(array('controller'=> 'users', 'action' => 'index'));
		}

		$accountId = $account['Account']['id'];
		if (!empty($account['Account']['account_id'])) {
			$accountId = $account['Account']['account_id'];
		}

		$data = array_merge(
			array(
				'User' => array(
					'username' => $this->Auth->user('username'),
					'account_id' => $accountId
				)),
			array(
				'access_token' => $token['AccessToken']['token'],
				'token_expire' => $token['AccessToken']['expired'])
		);

		$this->Session->write('Auth.Account.id', $accountId);
		if ($fixSecurity) {
			unset($data['User']['account_id']);
		}

		if ($return == true) {
			$data = json_encode(Hash::filter($data));
			return $data;
		}
		if (!empty($controller->request->params['ext']) && $controller->request->params['ext'] == 'json') {
			$controller->viewClass = 'json';
			$controller->response->header('Content-Type', 'application/json');
			$response['status'] = 0;
			$response['data'] = $data;
			$response['message'] = 'success';
			$controller->set('response', $response);
			$controller->set('_serialize', 'response');
			$controller->render();
			$controller->response->send();
			$this->_stop();
		}
	}

	public function authen_takan($action = 'login', $return = false, $fixSecurity = false)
	{
		$controller = $this->Controller;
		$app = $controller->request->header('app');
		$controller->loadModel('AccessToken');
		$controller->loadModel('Account');

		$userId = $this->Auth->user('id');
		if (!empty($userId)) {
			$token = $controller->AccessToken->generateToken($app, $userId);
		}

		$controller->Account->contain();
		$account = $controller->Account->findByUserIdAndGameId(
			$this->Auth->user('id'),
			$this->Common->currentGame('id')
		);

		if (empty($account)) {
			CakeLog::error('Không tìm thấy account này ' . $this->Auth->user('id') . ' - ' . $this->Common->currentGame('id'));
			$controller->Cookie->destroy();
			$controller->Session->destroy();
			$controller->Session->setFlash(__('Xin lỗi, đã có lỗi xảy ra!'), 'error');
			$controller->redirect(array('controller'=> 'users', 'action' => 'index'));
		}

		$accountId = $account['Account']['id'];
		if (!empty($account['Account']['account_id'])) {
			$accountId = $account['Account']['account_id'];
		}

		$data = array(
			'token' => $token['AccessToken']['token'],
			'uid'	=> $accountId,
			'account_id'	=> $accountId,
			'user_id'		=> $userId,
			'username' 	=> substr($this->Auth->user('username'),6)
		);

		$this->Session->write('Auth.Account.id', $accountId);
		if ($fixSecurity) {
			unset($data['User']['account_id']);
		}

		if ($return == true) {
			return $data;
		}
		if (!empty($controller->request->params['ext']) && $controller->request->params['ext'] == 'json') {
			$controller->viewClass = 'json';
			$controller->response->header('Content-Type', 'application/json');
			$response['status'] = 0;
			$response['data'] = $data;
			$response['message'] = 'success';
			$controller->set('response', $response);
			$controller->set('_serialize', 'response');
			$controller->render();
			$controller->response->send();
			$this->_stop();
		}
	}

	public function authen_vcc($action = 'login', $return = false, $fixSecurity = false)
	{
		$controller = $this->Controller;
		$app = $controller->request->header('app');
		$controller->loadModel('AccessToken');
		$controller->loadModel('Account');

		$userId = $this->Auth->user('id');
		if (!empty($userId)) {
			$token = $controller->AccessToken->generateToken($app, $userId);
		}

		$game = $this->Common->currentGame();

		$controller->Account->contain();
		$account = $controller->Account->findByUserIdAndGameId(
			$this->Auth->user('id'), $game['id']
		);

		if (empty($account)) {
			CakeLog::error('Không tìm thấy account này ' . $this->Auth->user('id') . ' - ' . $this->Common->currentGame('id'));
			$controller->Cookie->destroy();
			$controller->Session->destroy();
			$controller->Session->setFlash(__('Xin lỗi, đã có lỗi xảy ra!'), 'error');
			$controller->redirect(array('controller'=> 'users', 'action' => 'index'));
		}

		$accountId = $account['Account']['id'];
		if (!empty($account['Account']['account_id'])) {
			$accountId = $account['Account']['account_id'];
		}

		$data = array(
			"ipv4"		=> $this->Common->publicClientIp(),
			"indulge"	=> 1,
			'uname' 	=> substr($this->Auth->user('username'), 4),
			'uid'		=> $accountId,
			"KL_SSO"	=> $accountId,
			"KL_PERSON"	=> "HbzJXvrN14tizpsCilhL9zt-iNCtGzETlRdCLrEcfALa8k679L4vwQHMJN-5m-cOJm3Wqhg3-YE7EdI9-WX.SsCU49NIYeHUsLvq8anfi2GFO_AogqNkS6Uv4jQp.qxfgRdQnxpOzEeH_tpPLqWPlX_9kS1F5lb_c258dKhzKVG9.GJIlu-9l9_aqsvkGAK.pqkkDdvI6fP3uetKI7nJhSzSOyjpWuZSoGGVzlEvAG9R4gS3c3rlAQCZd58G5fxjC8sE9mSh.uGnOzOxuWAnx7QAdc_6d6Iva7Zou5YfpqM0",
			"isnew"		=> "true",

			'account_id'	=> $accountId,
			'user_id'		=> $userId,
			'token' 		=> $token['AccessToken']['token'],
			'app_key'		=> $game['app'],
			'app_secret'	=> $game['secret_key'],
		);

		$this->Session->write('Auth.Account.id', $accountId);
		if ($fixSecurity) {
			unset($data['User']['account_id']);
		}

		if ($return == true) {
			return $data;
		}
		if (!empty($controller->request->params['ext']) && $controller->request->params['ext'] == 'json') {
			$controller->viewClass = 'json';
			$controller->response->header('Content-Type', 'application/json');
			$response['status'] = 0;
			$response['data'] = $data;
			$response['message'] = 'success';
			$controller->set('response', $response);
			$controller->set('_serialize', 'response');
			$controller->render();
			$controller->response->send();
			$this->_stop();
		}
	}

	public function authen_v26($action = 'login', $return = false, $fixSecurity = false)
	{
		$controller = $this->Controller;
		$app = $controller->request->header('app');
		$controller->loadModel('AccessToken');
		$controller->loadModel('Account');

		$userId = $this->Auth->user('id');
		if (!empty($userId)) {
			$token = $controller->AccessToken->generateToken($app, $userId);
		}

		$game = $this->Common->currentGame();

		$controller->Account->contain();
		$account = $controller->Account->findByUserIdAndGameId(
			$this->Auth->user('id'), $game['id']
		);

		if (empty($account)) {
			CakeLog::error('Không tìm thấy account này ' . $this->Auth->user('id') . ' - ' . $this->Common->currentGame('id'));
			$controller->Cookie->destroy();
			$controller->Session->destroy();
			$controller->Session->setFlash(__('Xin lỗi, đã có lỗi xảy ra!'), 'error');
			$controller->redirect(array('controller'=> 'users', 'action' => 'index'));
		}

		$accountId = $account['Account']['id'];
		if (!empty($account['Account']['account_id'])) {
			$accountId = $account['Account']['account_id'];
		}

		$data = array(
//			"ipv4"		=> $this->Common->publicClientIp(),
//			"indulge"	=> 1,
//			'uname' 	=> substr($this->Auth->user('username'), 4),
//			'uid'		=> substr($this->Auth->user('username'), 4),
//			"KL_SSO"	=> "HbzJXvrN14tizpsCilhL9zt-iNCtGzETlRdCLrEcfALa8k679L4vwQHMJN-5m-cOJm3Wqhg3-YE7EdI9-WX.SsCU49NIYeHUsLvq8anfi2GFO_AogqNkS6Uv4jQp.qxfgRdQnxpOzEeH_tpPLqWPlX_9kS1F5lb_c258dKhzKVG9.GJIlu-9l9_aqsvkGAK.pqkkDdvI6fP3uetKI7nJhSzSOyjpWuZSoGGVzlEvAG9R4gS3c3rlAQCZd58G5fxjC8sE9mSh.uGnOzOxuWAnx7QAdc_6d6Iva7Zou5YfpqM0",
//			"KL_PERSON"	=> "HbzJXvrN14tizpsCilhL9zt-iNCtGzETlRdCLrEcfALa8k679L4vwQHMJN-5m-cOJm3Wqhg3-YE7EdI9-WX.SsCU49NIYeHUsLvq8anfi2GFO_AogqNkS6Uv4jQp.qxfgRdQnxpOzEeH_tpPLqWPlX_9kS1F5lb_c258dKhzKVG9.GJIlu-9l9_aqsvkGAK.pqkkDdvI6fP3uetKI7nJhSzSOyjpWuZSoGGVzlEvAG9R4gS3c3rlAQCZd58G5fxjC8sE9mSh.uGnOzOxuWAnx7QAdc_6d6Iva7Zou5YfpqM0",
//
//			'account_id'	=> $accountId,
//			'user_id'		=> $userId,
//			'token' 		=> $token['AccessToken']['token'],
//			'app_key'		=> $game['app'],
//			'app_secret'	=> $game['secret_key'],
			"ipv4"		=> $this->Common->publicClientIp(),
			"indulge"	=> 1,
			'uname' 	=> substr($this->Auth->user('username'), 4),
			'uid'		=> $accountId,
			"KL_SSO"	=> base64_encode(substr($this->Auth->user('username'), 4)),
			"KL_PERSON"	=> base64_encode(substr($this->Auth->user('username'), 4)),
			"isnew"		=> "true",

			'account_id'	=> $accountId,
			'user_id'		=> $userId,
			'token' 		=> $token['AccessToken']['token'],
			'app_key'		=> $game['app'],
			'app_secret'	=> $game['secret_key'],
		);

		$this->Session->write('Auth.Account.id', $accountId);
		if ($fixSecurity) {
			unset($data['User']['account_id']);
		}

		if ($return == true) {
			return $data;
		}
		if (!empty($controller->request->params['ext']) && $controller->request->params['ext'] == 'json') {
			$controller->viewClass = 'json';
			$controller->response->header('Content-Type', 'application/json');
			$response['status'] = 0;
			$response['data'] = $data;
			$response['message'] = 'success';
			$controller->set('response', $response);
			$controller->set('_serialize', 'response');
			$controller->render();
			$controller->response->send();
			$this->_stop();
		}
	}
}
