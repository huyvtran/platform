<?php

App::uses('CakeTime', 'Utility');
App::uses('AppController', 'Controller');

class InstallsController extends AppController {

	public $components = array('Search.Prg');

	public $uses = array('LogInstallByDay');

	public $useModel = 'LogInstallByDay';

	public $presetVars = true;

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	public function index()
	{
		$this->indexDefault();
	}
}