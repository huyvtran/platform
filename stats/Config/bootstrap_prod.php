<?php

Cache::config('stats', array(
	'engine' => 'File',
	'duration' => '+24 hours',
	'probability' => 100,
	'prefix' => 'stats_stats_',
));


# Sesssion's configs
Cache::config('sessions_cache', array(
	'engine' => 'File',
	'duration'=> 216000,
	'compress' => false,
	'persistent' => true,
));

Configure::write('Session', array(
	'defaults' => 'cache',
	'handler' => array(
		'config' => 'sessions_cache'
	),
	'cookie' => 'MYAPP_PROD_STATS',
	'checkAgent' => false,
	'timeout' => 3600,
	'ini' => array(
		'session.cookie_domain' => 'stats.muoriginfree.com',
		'session.gc_maxlifetime' => 21600
	)
));