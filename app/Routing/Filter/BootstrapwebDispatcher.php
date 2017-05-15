<?php

App::uses('DispatcherFilter', 'Routing');

class BootstrapwebDispatcher extends DispatcherFilter {

	public $priority = 1;

	public function beforeDispatch(CakeEvent $event)
	{
		if (!empty($_SERVER['HTTP_HOST'])) {
			if (strpos(env('REQUEST_URI'), 'LinkTrackings') == false && strpos(env('REQUEST_URI'), 'emailFeedbacks') == false) {

				# use for localhost dev
				if ($_SERVER['SERVER_NAME'] == 'localhost') {
					$host = 'localhost';
				} else {
					$host = $_SERVER['HTTP_HOST'];
				}
				$file = APP . 'Event' . DS . 'Bootstrap' . DS . $host . '.php';
				if (file_exists($file)) {
					include $file;
				}
			}
		}
	}
}
