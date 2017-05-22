<?php

ini_set('newrelic.appname', 'mobgame-stats');

Cache::config('stats', array(
	'engine' => 'Memcached',
    'probability' => 100,
	'duration' => '+24 hours',
	'probability' => 100,
	'prefix' => 'stats_stats_',
	'servers' => array(
		'plf2.isslr5.0001.apse1.cache.amazonaws.com:11211'
	),
	'persistent' => true,
	'compress' => true,
));