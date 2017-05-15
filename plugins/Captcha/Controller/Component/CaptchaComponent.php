<?php

App::uses('Component', 'Controller');

class CaptchaComponent extends Component {

	public $components = array('Session');

	public function verify($captcha)
	{
		return strtolower($this->Session->read('secretword')) == strtolower($captcha);
	}

}
