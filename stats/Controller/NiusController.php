<?php

App::uses('CakeTime', 'Utility');
App::uses('AppController', 'Controller');

class NiusController extends AppController {

	public $components = array('Search.Prg');
	
	public $uses = array('LogAccountsByDay');
	
	public $useModel = 'LogAccountsByDay';
	
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