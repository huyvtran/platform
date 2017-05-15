<?php

App::uses('CaptchaAppController', 'Captcha.Controller');

class CaptchaController extends CaptchaAppController {

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow();
	}

	public function view()
	{
		require_once ROOT . DS . 'plugins' . DS . 'Captcha' . DS . 'Vendor' . DS . 'cool-php-captcha-0.3.1' . DS . 'captcha.php';
		$captcha = new SimpleCaptcha();
		$captcha->wordsFile = 'words/es.php';
		$captcha->resourcesPath = ROOT . DS . 'plugins' . DS . 'Captcha' . DS . 'Vendor' . DS . 'cool-php-captcha-0.3.1' . DS . 'resources';
		$captcha->session_var = 'secretword';
		$captcha->imageFormat = 'png';
		$captcha->width = 130;
		$captcha->CreateImage();
		$this->autoRender = false;
		exit();
	}
}

