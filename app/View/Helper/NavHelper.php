<?php

App::uses('AppHelper', 'Helper');
App::uses('Utility', 'Lib');

class NavHelper extends AppHelper {
	
	public $helpers = array(
		'Time', 'Html', 'Utils.Gravatar', 'Form', 'Imagine.Imagine', 'Session'
	);

	public function afterLayout($layoutFile)
	{
		if ($this->request->prefix == 'admin') {
			ini_set('memory_limit', '512M');
			ini_set('max_execution_time', 120);
		}
	}

	function thisController($controller, $string = null)
	{
		$controller = (array) $controller;
		$controller = array_map('strtolower', $controller);

		if(in_array(strtolower($this->request->params['controller']), $controller)) {
			if (!$string)
				echo 'active';
		}
	}
}