<?php

/**
 * Wrap for Redis libary: https://github.com/nicolasff/phpredis
 * Read configs from cakephp's config
 **/
class RedisCake {

	public $configName;


	public function __construct($configName)
	{
		if (!isset($configName)) {
			throw new Exception('Missing params for redis instance');
		}
		$this->configName = $configName;
		$settings = Configure::read('Redis_Configs.' . $configName);
		if (empty($settings)) {
			throw new Exception('Can not read this config');
		}
		$this->_Redis = new Redis();
		$this->_Redis->pconnect($settings['server'], $settings['port'], $settings['timeout']);
		$this->_Redis->setOption(Redis::OPT_PREFIX, $settings['port']['prefix']);
		$this->_Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
		return $this->_Redis;
	}

    public function __call($method, $args) 
	{
		return call_user_func_array(array($this->_Redis, $method), $args);
	}

}