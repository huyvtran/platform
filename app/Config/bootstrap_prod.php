<?php

Configure::write('Routing.prefixes', array('admin', 'user', 'api'));

CakePlugin::loadAll(array(
	'DebugKit', 'Captcha', 'MarkupParsers',
	'Search', 'Tags', 'Utils',
	'Imagine' => array('bootstrap' => true),
	'CakeResque' => array(
		'bootstrap' => array(
			'bootstrap_config',
			'../../../app/Config/bootstrap_prod_resque',
			'bootstrap'
	))
));

Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'MyCacheDispatcher',
	'BootstrapwebDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');

$logEngine = 'FileLog';
if (php_sapi_name() === 'cli' && !empty($_SERVER['testing'])){
	$logEngine = 'ConsoleLog';
}
CakeLog::config('info', array(
	'engine' => $logEngine,
	'types' => array('info', 'debug'),
	'file' => 'info',
));

CakeLog::config('debug', array(
	'engine' => $logEngine,
	'types' => array('notice', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => $logEngine,
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

CakeLog::config('request', array(
	'engine' => $logEngine,
	'types' => array('info', 'debug'),
	'scopes' => array('request'),
	'file' => 'request.log'
));

CakeLog::config('payment', array(
    'engine' => $logEngine,
    'types' => array('info', 'debug', 'error'),
    'scopes' => array('payment'),
    'file' => 'payment',
));

CakeLog::config('user', array(
    'engine' => $logEngine,
    'types' => array('info', 'debug', 'error'),
    'scopes' => array('user'),
    'file' => 'user',
));

$engine = 'File';

if (extension_loaded('apc') && php_sapi_name() !== 'cli'){
	$engine = 'Apc';
}

$cacheConfigs = array(
	'default' => array(
		'duration' => '+1 hour'
	),
	'file' => array(
		'engine' => 'File',
		'duration' => '+1 month'
	),
	'short' => array(
		'prefix' => 'short_',
		'duration' => '+5 minutes'
	),
	'medium' => array(
		'prefix' => 'medium_',
		'duration' => '+1 hour'		
	),
	'long' => array(
		'prefix' => 'long_',
		'duration' => '+1 day'
	),
	'info' => array(
		'duration' => '+1 hour',
		'prefix' => 'detect_',
	),
    'email' => array(
        'duration' => '+6 hours',
        'prefix' => 'email_'
    )
);

foreach($cacheConfigs as $key => $config) {
	$default = array(
		'engine' => 'File'	
	);
	Cache::config($key, array_merge($default, $config));
}

Configure::write('Session', array(
	'defaults' => 'Memcache',
	'cookie' => 'MYAPP_PROD',
	'timeout' => 3600,
	'checkAgent' => false,
));

Configure::write("Redis_Configs", array(
	'action_count' => array(
		'engine' => 'Redis',
		'prefix' => 'action_count',
		'server' => '127.0.0.1',
		'port' => 6379,
		'duration' => '+48 hours',
		'timeout' => 1,
		'persistent' => true
	),
	'fullpage_clearcache' => array(  # use for pubsub to clear cache
		'engine' => 'Redis',
		'prefix' => 'fullpage_clearcache',
		'server' => '127.0.0.1',
		'port' => 6379,
		'duration' => '+1 hours',
		'timeout' => 3600, # a long time connection for pub sub
		'persistent' => true
	),
));

Configure::write('Queue', array(
	'default' => array(
		'engine' => 'Redis',
		'prefix' => 'queue_default',
		'server' => '127.0.0.1',
		'port' => 6379,
		'duration' => '+48 hours',
		'timeout' => 1,
		'persistent' => true
	)
));

Configure::write('LinkTracking', array(
    'default' => array(
        'engine' => 'Redis',
        'prefix' => 'link_tracking_default',
        'server' => '127.0.0.1',
        'port' => 6379,
        'duration' => '+48 hours',
        'timeout' => 1,
        'persistent' => true
    )
));

Configure::write('EmailMarketing', array(
    'default' => array(
        'engine' => 'Redis',
        'prefix' => 'email_marketing_default',
        'server' => '127.0.0.1',
        'port' => 6379,
        'duration' => '+48 hours',
        'timeout' => 1,
        'persistent' => true
    )
));

if (!empty($_GET['app'])) {
	$_SERVER['HTTP_APP'] = $_GET['app'];
}
if (!empty($_GET['token'])) {
	$_SERVER['HTTP_TOKEN'] = $_GET['token'];
}

foreach($_GET as $key => $getParam){
	if (stripos($key, 'app_game_') == 0) {
		if (is_string($getParam)) {
			$_SERVER[strtoupper(str_ireplace('app_game_', 'HTTP_APP_GAME_', $key))] = $getParam;
		}
	}

	if (stripos($key, 'app-game-') == 0) {
		if (is_string($getParam)) {
			$_SERVER[strtoupper(str_replace('-', '_', str_ireplace('app-game-', 'HTTP_APP_GAME_', $key)))] = $getParam;
		}
	}
}

Configure::write('VippayBanking', array(
	'ReturnUrl' => 'http://admin.muoriginfree.com:8880/OvsPayments/pay_vippay_response',
));

Configure::write('OnepayBanking', array(
	'ReturnUrl' => 'http://admin.muoriginfree.com:8880/OvsPayments/pay_onepay_response',
));

Configure::write('Paymentwall', array(
	'ReturnUrl' => 'http://admin.muoriginfree.com:8880/OvsPayments/pay_paymentwall_wait',
    'UrlPingBack' => 'http://admin.muoriginfree.com:8880/OvsPayments/pay_paymentwall_response.json',
));