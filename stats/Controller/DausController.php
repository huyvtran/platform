<?php

App::uses('CakeTime', 'Utility');
App::uses('AppController', 'Controller');

class DausController extends AppController {

	public $components = array('Search.Prg');

	public $uses = array('LogLoginsByDay', 'LogEntergamesServerByDay');

	public $useModel = 'LogLoginsByDay';

	public $presetVars = true;

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	public function index()
	{
		$this->indexDefault();
	}

    public function country()
    {
        $this->modelClass = 'LogLoginsCountryByDay';
        $this->indexCountry();
    }

    public function monthly() {
        $this->modelClass = 'LogLoginsByMonth';
        $this->monthlyDefault();
    }

    public function quarter() {
        $this->modelClass = 'LogLoginsByQuarter';
        $this->quarterYearDefault();
    }
}