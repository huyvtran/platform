<?php
App::uses('Router', 'Routing');

Router::parseExtensions('json');

Configure::write(array(
	'Pusher' => array(
		'credentials' => array(
			'appKey' => 'c4274ca961780e25b71b',
			'appSecret' => '49f9a2b39afb77dff184',
			'appId' => '235558',
		),
		'channelAuthEndpoint' => array(
			'plugin' => 'pusher',
			'controller' => 'pusher',
			'action' => 'auth.json',
		)
	)
));

?>