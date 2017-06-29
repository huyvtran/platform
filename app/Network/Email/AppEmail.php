<?php

class AppEmail extends CakeEmail {
    public function __construct($config = null)
    {
        parent::__construct($config);

        $key = 'count_email_marketing_all_game';
        App::import('Lib', 'RedisCake');
        $Redis = new RedisCake('action_count');
        $Redis->incr($key);
        $Redis->expire($key, 60*60);
    }

    public function getConfig(){
        return $this->_config;
    }
}
