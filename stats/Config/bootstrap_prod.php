<?php

ini_set('newrelic.appname', 'mobgame-stats');

Configure::write('Fluentd', array('host' => '10.0.0.45', 'port' => 24224));

Cache::config('stats', array(
	'engine' => 'Memcached',
    'probability' => 100,
	'duration' => '+24 hours',
	'probability' => 100,
	'prefix' => 'stats_stats_',
	'servers' => array(
		'plf-data-0411-1414.isslr5.0001.apse1.cache.amazonaws.com:11211'
	),
	'persistent' => true,
	'compress' => true,
));

# Sesssion's configs
Cache::config('sessions_cache', array(
	'engine' => 'Memcache',
	'duration'=> 216000,
	'servers' => array('plf-session-1.isslr5.cfg.apse1.cache.amazonaws.com:11211' ),
	'compress' => false,
	'persistent' => true,
));

Configure::write('Session', array(
		'defaults' => 'cache',
    	'handler' => array(
			'config' => 'sessions_cache'
		),	
        'cookie' => 'MOBGAME_STATS',
        'checkAgent' => false,
        'timeout' => 3600,
        'ini' => array(
                'session.cookie_domain' => 'stats.smobgame.com',
                'session.gc_maxlifetime' => 21600
        )
));