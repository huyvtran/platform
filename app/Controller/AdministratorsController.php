<?php

use Proxy\Http\Request;
use Proxy\Proxy;

App::uses('CakeEmail', 'Network/Email');
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
App::uses('Security', 'Utility');
App::uses('Inflector', 'Utility');
App::import('Lib', 'RedisCake');

class AdministratorsController extends AppController{

	public $uses = false;

	public $components = array(
		'Security' => array(
			'csrfExpires' => '+180 minutes',
			'csrfUseOnce' => false
		)
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'default_bootstrap';
		$this->Auth->allow(array('admin_index', 'printHeader'));
	}

	public function admin_info()
	{
		echo phpinfo();
		exit();
	}

	public function printHeader()
	{
		Configure::write('debug', 2);
		debug($this->request->header($this->request->query('get')));
		debug($_SERVER);
		$this->autoRender = false;
	}

	public function admin_readLog($name = null)
	{
		ini_set('memory_limit', '512M');
		if ($name) {
			$lines = $this->request->query('lines');
			if (empty($lines))
				$lines = 1000;

			ob_start();
			passthru("tail -n $lines " . TMP . 'logs' . DS . $name, $result);
			$content = ob_get_clean();
			$this->set(compact('content'));
		}
	}

    public function admin_clearLogs()
    {
        exec('cat /dev/null > ' . TMP . 'logs' . DS . 'info.log');
        exec('cat /dev/null > ' . TMP . 'logs' . DS . 'debug.log');
        exec('cat /dev/null > ' . TMP . 'logs' . DS . 'error.log');
        $this->Session->setFlash('Đã xóa trắng file log debug và error','success');
        $this->redirect(array('action' => 'index'));
    }

	public function admin_redis()
	{
		$configs = Configure::read('Redis_Configs');
		$checkDuplicate = array();
		foreach ($configs as $key => $config) {
			if (in_array($config['server'], $checkDuplicate)) {
				continue;
			}
			$uniqueConfigs[] = $config;
			$checkDuplicate[] = $config['server'];
		}

		$detect_redis = array( 'llen' => 'lRange', 'zCard' => 'zRange',
			'get' => 'incr', 'hLen' => 'hGetAll', 'sCard' => 'sScan'
		);
		$result = array();
		foreach ($uniqueConfigs as $key => $config) {
			$Redis = new Redis();
			$Redis->pconnect($config['server'], $config['port'], $config['timeout']);
			$Redis->setOption(Redis::OPT_PREFIX, $config['port']['prefix']);
			$Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
			$keys = $Redis->keys("*");

			$keys = Hash::sort($keys, '{n}', 'DESC');
			foreach ($keys as $k => $value) {
				$type = 'Not found / other';
				switch ($Redis->type($value)){
					case Redis::REDIS_STRING:
						$type = 'String';
						break;
					case Redis::REDIS_SET:
						$type = 'Set';
						break;
					case Redis::REDIS_LIST:
						$type = 'List';
						break;
					case Redis::REDIS_ZSET:
						$type = 'Sorted set';
						break;
					case Redis::REDIS_HASH:
						$type = 'Hash';
						break;
				}
				$result[$config['server']][$value] = false;
				foreach ($detect_redis as $action_key => $action){
					$tmp = $Redis->{$action_key}($value);
					if(!empty($tmp)){
						$result[$config['server']][$value]['count'] = $tmp;
						$result[$config['server']][$value]['func'] = $action;
						$result[$config['server']][$value]['type'] = $type;
						break;
					}
				}
			}
		}

		$this->set(compact('result'));		
	}

	public function admin_redis_detail(){

		if(empty($this->request->params['named'])){
			throw new BadRequestException('Can not found key and server');
		}
		$key = $this->request->params['named']['key'];
		$server = $this->request->params['named']['server'];
		$action = $this->request->params['named']['type'];

		$ReDisConfig = false;
		$configs = Configure::read('Redis_Configs');
		foreach ($configs as $config) {
			if ($config['server'] == $server) {
				$ReDisConfig = $config;
				break;
			}
		}

		if(!$ReDisConfig){
			throw new BadRequestException('Can not found redis server');
		}

		$Redis = new Redis();
		$Redis->pconnect($ReDisConfig['server'], $ReDisConfig['port'], $ReDisConfig['timeout']);
		$Redis->setOption(Redis::OPT_PREFIX, $ReDisConfig['port']['prefix']);
		$Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

		if ($action == 'hGetAll') $results = $Redis->hGetAll($key);
		elseif ($action == 'sScan') $results = $Redis->sMembers($key);
		else $results = $Redis->{$action}($key, 0, 1000);

		$this->set(compact('results'));
	}

	public function _run($cmd, $print = false)
	{
		exec($cmd . "  2>&1", $output, $result);
		$screen = '';
		if ($print || $result !== 0) {
			$screen .= "<pre>";
			if ($result !== 0) {
				$screen .= $cmd . ": ";
			}
			$screen .= implode("\n", $output);
			$screen .= "</pre>";
		}

		return array($output, $result, $screen);
	}

	public function admin_cmd()
	{
		if ($this->request->is('post')) {
			list($output, $result, $screen) = $this->_run($this->request->data['cmd'], true);
			$this->Session->setFlash('Executed command !', 'success');
			$this->set(compact('output', 'result', 'screen'));
		}
	}

	public function admin_index()
	{
		$role = $this->Auth->user('role');
		if (in_array($role, array('User', 'Guest')) || !$this->Auth->loggedIn()) {
			$this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => false));
		}
	}
}
