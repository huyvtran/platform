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
			'uid'	=> $this->Auth->user('id'),
			'username' => $this->Auth->user('username')
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
