<?php
App::uses('Component', 'Controller');

class LogComponent extends Component {

	public $components = array(
		'Auth', 'Session', 'Common'
	);

	public function initialize(Controller $controller)
	{
		$this->Controller = $controller;
	}

	public function logLogin()
	{
		$this->Controller->loadModel('LogLogin');

		$data['game_id'] = $this->Common->currentGame('id');
		$data['user_id'] = $this->Auth->user('id');

		$data['ip'] = $this->Common->publicClientIp();
		$data['os'] = $this->Controller->request->header('os');
		$data['resolution'] = $this->Controller->request->header('resolution');
		$data['sdk_ver'] = $this->Controller->request->header('sdk_version');
		$data['g_ver'] = $this->Controller->request->header('app_version');
		$data['network'] = $this->Controller->request->header('network');
		$data['device'] = $this->Controller->request->header('device');
		$data['created'] = date('Y-m-d H:i:s');

		if (	empty($data['user_id'])
			||	empty($data['game_id'])
		) {
			CakeLog::info('error save log login');
			return false;
		}

		try {
			$Redis = $this->connect();
			$Redis->rPush('default', array(
				'model' => 'LogLogin',
				'data' => $data
			));
		} catch (Exception $e) {
			CakeLog::error($e->getMessage());
			return false;
		}

		return true;
	}

	public function logEnterGame()
	{
		$this->Controller->loadModel('LogEntergame');
		$data['game_id'] = $this->Common->currentGame('id');
		$data['user_id'] = $this->Auth->user('id');
		$data['role_id'] = $this->Controller->request->header('role_id');
		$data['area_id'] = $this->Controller->request->header('area_id');

		$data['ip'] = $this->Common->publicClientIp();
		$data['os'] = $this->Controller->request->header('os');
		$data['sdk_ver'] = $this->Controller->request->header('sdk_version');
		$data['g_ver'] = $this->Controller->request->header('app_version');
		$data['network'] = $this->Controller->request->header('network');
		$data['device'] = $this->Controller->request->header('device');
		$data['created'] = date('Y-m-d H:i:s');

		if (empty($data['role_id'])) {
			$data['role_id'] = $this->Controller->request->query('role_id');
		}
		if (empty($data['area_id'])) {
			$data['area_id'] = $this->Controller->request->query('area_id');
		}
		if (	empty($data['role_id'])
			||	empty($data['area_id'])
			||	empty($data['game_id'])
			||	empty($data['user_id'])
		) {
			return false;
		}
		try {
			$Redis = $this->connect();
			$Redis->rPush('default', array(
				'model' => 'LogEntergame',
				'data' => $data
			));

		} catch (Exception $e) {
			CakeLog::error($e->getMessage());
			return false;
		}
		return true;
	}

	public function connect()
	{
		if (!isset($this->_Redis)) {
			$settings = Configure::read('Queue.default');
			$this->_Redis = new Redis();
			$this->_Redis->pconnect($settings['server'], $settings['port'], $settings['timeout']);
			$this->_Redis->setOption(Redis::OPT_PREFIX, $settings['port']['prefix']);
			$this->_Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
		}
		return $this->_Redis;
	}
}