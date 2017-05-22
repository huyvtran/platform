<?php

class DATABASE_CONFIG {

    public $default = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'platform-rep1.cstko6cbzjfj.ap-southeast-1.rds.amazonaws.com',
        'login' => 'platform',
        'password' => 'PlatForm9',
        'database' => 'platform',
        'prefix' => '',
        'encoding' => 'utf8',
    );

    public $master = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'platform.cstko6cbzjfj.ap-southeast-1.rds.amazonaws.com',
        'login' => 'platform',
        'password' => 'PlatForm9',
        'database' => 'platform',
        'prefix' => '',
        'encoding' => 'utf8'
    );

    public $test = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => '10.131.43.17',
        'login' => 'platform',
        'password' => 'PlatForm9',
        'database' => 'platform_test',
        'prefix' => '',
        'encoding' => 'utf8',
    );
}