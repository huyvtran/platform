<?php

App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {

	protected function _outputMessage($template)
	{
		if (Configure::read('debug') != 0 || !empty($this->controller->request->params['admin'])) {
			$this->controller->layout = 'default_bootstrap';
		}
		parent::_outputMessage($template);
	}

	public function error400($error)
	{
		if ($error->getMessage() == 'The request has been black-holed') {
			CakeLog::info('data blackhode' . print_r($this->controller->request->data, true));
		}
		if (Configure::read('debug') == 0 && empty($this->controller->request->params['admin'])) {
			$this->controller->Common->setTheme();
			if ($this->controller->theme == 'Dashboard') {
				$this->controller->layout = 'dashboard';
			}
		} else {
			$this->controller->layout = 'default_bootstrap'; 
		}
		$message = $error->getMessage();
		if (!Configure::read('debug') && $error instanceof CakeException) {
			$message = __d('cake', 'Not Found');
		}
		$url = $this->controller->request->here();
		$this->controller->response->statusCode($error->getCode());
		$this->controller->set(array(
			'name' => h($message),
			'url' => h($url),
			'error' => $error,
			'code' => $error->getCode(),
			'message' => h($message),
			'_serialize' => array('name', 'url', 'message', 'code')
		));
		$this->_outputMessage('error400');
	} 

	public function error500($error)
	{
		if (Configure::read('debug') == 0 && empty($this->controller->request->params['admin'])) {
			$this->controller->Common->setTheme();
			if ($this->controller->theme == 'Dashboard') {
				$this->controller->layout = 'dashboard';
			}
		} else {
			$this->controller->layout = 'default_bootstrap'; 
		}
		$message = $error->getMessage();
		if (!Configure::read('debug')) {
			$message = __d('cake', 'An Internal Error Has Occurred.');
		}
		$url = $this->controller->request->here();
		$code = ($error->getCode() > 500 && $error->getCode() < 506) ? $error->getCode() : 500;
		$this->controller->response->statusCode($code);
		$this->controller->set(array(
			'name' => h($message),
			'message' => h($url),
			'error' => $error,
			'code' => $code,
			'_serialize' => array('name', 'message', 'code')
		));
		$this->_outputMessage('error500');
	} 

}