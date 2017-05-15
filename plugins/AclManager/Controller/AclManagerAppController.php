<?php

class AclManagerAppController extends AppController {

	public function beforeFilter()
	{
		parent::beforeFilter();
		$prefix = Configure::read('AclManager.prefix');
		$routePrefix = isset($this->request->params['prefix']) ? $this->request->params['prefix'] : false;
		if ($prefix && $prefix != $routePrefix) {
			$this->redirect($this->request->referer());
		} 
		elseif ($prefix) {
			$this->request->params['action'] = str_replace($prefix . "_", "", $this->request->params['action']);
			$this->view = str_replace($prefix . "_", "", $this->view);
		}
	}
}

