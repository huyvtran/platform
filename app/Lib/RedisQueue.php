<?php

class RedisQueue {

	public $configName;

	public $key = 'default';

	public function __construct($configName = null, $key = 'default')
	{
		if (!isset($configName)) {
			$configName = 'default';
		}
		$this->key = $key;
		$this->configName = $configName;
		if (!isset($this->_Redis)) {
			$settings = Configure::read('Queue.' . $configName);
			if (empty($settings)) {
				throw new Exception('Can not read this config');
			}
			$this->_Redis = new Redis();
			$this->_Redis->pconnect($settings['server'], $settings['port'], $settings['timeout']);
			$this->_Redis->setOption(Redis::OPT_PREFIX, $settings['port']['prefix']);
			$this->_Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
		}
		return $this->_Redis;
	}

    public function __call($func, $params)
    {
    	array_unshift($params, $this->key);
    	return call_user_func_array(array($this->_Redis, $func), $params);
    }
}